<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Discounts;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discounts::with('product', 'user')->latest()->get();
        $products = Product::all();
        $users = User::all();
        return view('view-menu-dashboard.diskon', compact('discounts', 'products', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:product,voucher',
            'discount_type' => 'nullable|in:percent,nominal,free_shipping,shipping_discount',
            'value' => 'nullable|numeric',
            'product_id' => 'nullable|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'expired_at' => 'nullable|date',
        ]);

        // Default status
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle value berdasarkan tipe diskon
        if ($data['discount_type'] === 'free_shipping') {
            $data['value'] = null;
        }

        Discounts::create($data);

        return redirect()->back()->with('success', 'Diskon/Voucher berhasil dibuat.');
    }

    public function update(Request $request, Discounts $discount)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:product,voucher',
            'discount_type' => 'nullable|in:percent,nominal,free_shipping,shipping_discount',
            'value' => 'nullable|numeric',
            'product_id' => 'nullable|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'expired_at' => 'nullable|date',
        ]);

        // Default status
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle value berdasarkan tipe diskon
        if ($data['discount_type'] === 'free_shipping') {
            $data['value'] = null;
        }

        $discount->update($data);

        return redirect()->back()->with('success', 'Diskon/Voucher berhasil diperbarui.');
    }

    public function destroy(Discounts $discount)
    {
        $discount->delete();
        return redirect()->back()->with('success', 'Diskon/Voucher berhasil dihapus.');
    }


    // 🔎 Cek voucher valid/tidak
    public function check(Request $request)
    {
        $query = Discounts::where('type', 'voucher')
            ->where('status', 1) // ✅ hanya aktif
            ->where(function($q){
                $q->whereNull('expired_at')
                ->orWhere('expired_at', '>=', now()); // ✅ tidak expired
            });

        // kalau dikirim kode voucher, filter per kode
        if ($request->code) {
            $query->where('name', $request->code);
        }

        $vouchers = $query->get();

        if ($vouchers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada voucher tersedia'
            ]);
        }

        // tambahkan info klaim & used untuk user
        $result = $vouchers->map(function($voucher) {
            $claimed = VoucherClaim::where('voucher_id', $voucher->id)
                ->where('user_id', Auth::id())
                ->exists();

            $used = VoucherClaim::where('voucher_id', $voucher->id)
                ->where('user_id', Auth::id())
                ->whereNotNull('used_at')
                ->exists();

            return [
                'id' => $voucher->id,
                'name' => $voucher->name,
                'value' => $voucher->value,
                'discount_type' => $voucher->discount_type,
                'claimed' => $claimed,
                'used' => $used,
            ];
        });

        return response()->json([
            'success' => true,
            'vouchers' => $result
        ]);
    }


    // 📌 Klaim voucher
    public function claim(Request $request)
    {
        $voucher = Discounts::where('id', $request->voucher_id)
            ->where('type', 'voucher')
            ->where('status', 1) // hanya voucher aktif
            ->where(function($q) {
                $q->whereNull('expired_at')
                ->orWhere('expired_at', '>=', now()); // tidak expired
            })
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak aktif atau sudah expired'
            ]);
        }

        // cek kalau sudah klaim
        $already = VoucherClaim::where('voucher_id', $voucher->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($already) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah klaim voucher ini'
            ]);
        }

        VoucherClaim::create([
            'voucher_id' => $voucher->id,
            'user_id' => Auth::id(),
            'claimed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diklaim'
        ]);
    }


    // 💰 Gunakan voucher saat checkout
    public function use(Request $request)
    {
        $voucherIds = $request->vouchers ?? [];
        $subtotal = $request->subtotal;
        $shipping = $request->shipping_cost;

        $diskonSubtotal = 0;
        $diskonShipping = 0;

        foreach ($voucherIds as $id) {
            $voucher = Discounts::where('id', $id)
                ->where('type', 'voucher')
                ->where('status', 1)
                ->where(function($q) {
                    $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>=', now());
                })
                ->first();

            if (!$voucher) continue;

            $claim = VoucherClaim::where('voucher_id', $voucher->id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$claim || $claim->used_at) continue;

            if ($voucher->discount_type === 'percent') {
                $diskonSubtotal += ($voucher->value / 100) * $subtotal;
            } elseif ($voucher->discount_type === 'nominal') {
                $diskonSubtotal += $voucher->value;
            } elseif ($voucher->discount_type === 'free_shipping') {
                $diskonShipping += $shipping;
            } elseif ($voucher->discount_type === 'shipping_discount') {
                $diskonShipping += min($voucher->value, $shipping);
            }

            $claim->update(['used_at' => now()]);
        }

        $finalSubtotal = max(0, $subtotal - $diskonSubtotal);
        $finalShipping = max(0, $shipping - $diskonShipping);
        $finalTotal = $finalSubtotal + $finalShipping;

        return response()->json([
            'success' => true,
            'diskon_subtotal' => $diskonSubtotal,
            'diskon_shipping' => $diskonShipping,
            'total_discount' => $diskonSubtotal + $diskonShipping,
            'new_subtotal' => $finalSubtotal,
            'new_shipping' => $finalShipping,
            'new_total' => $finalTotal,
        ]);
    }


    public function apply(Request $request)
    {
        $voucherIds = $request->vouchers ?? [];

        if (empty($voucherIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada voucher dipilih'
            ]);
        }

        $validVouchers = Discounts::whereIn('id', $voucherIds)
            ->where('type', 'voucher')
            ->get();

        if ($validVouchers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid'
            ]);
        }

        $totalDiscount = 0;
        foreach ($validVouchers as $voucher) {
            if ($voucher->discount_type == 'percent') {
                $totalDiscount += ($request->subtotal * $voucher->value / 100);
            } else {
                $totalDiscount += $voucher->value;
            }

            // 🔹 tandai voucher langsung digunakan
            VoucherClaim::where('user_id', auth()->id())
                ->where('voucher_id', $voucher->id)
                ->whereNull('used_at')
                ->update(['used_at' => now()]);
        }

        session([
            'applied_vouchers' => $voucherIds,
            'total_discount'   => $totalDiscount
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Voucher berhasil digunakan',
            'discount' => $totalDiscount
        ]);
    }


}
