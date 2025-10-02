<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Kurir;
use App\Models\Order;
use App\Models\Product;
use App\Models\Discounts;
use Illuminate\Support\Str;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use App\Models\User_Addresses;
use App\Models\Payment_methods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::id();

        // 🔑 Kalau checkout datang dari Cart, hapus session Buy Now
        if ($request->query('source') === 'cart') {
            session()->forget('checkout_buy_now');
        }

        $kurir          = Kurir::all();
        $addresses      = User_Addresses::where('user_id', $userId)->get();
        $paymentMethods = Payment_methods::where('is_active', true)->get();

        $cart = collect();
        $subtotal   = 0;
        $totalBerat = 0;

        if (session()->has('checkout_buy_now')) {
            // ✅ prioritas Buy Now
            $buyNow  = session('checkout_buy_now');
            $product = Product::find($buyNow['product_id']);

            if ($product) {
                $dummyCart = new Cart([
                    'id'        => 0,
                    'user_id'   => $userId,
                    'product_id'=> $product->id,
                    'jumlah'    => $buyNow['jumlah'],
                ]);
                $dummyCart->setRelation('product', $product);
                $cart = collect([$dummyCart]);
            }
        } elseif (session()->has('checkout_cart_ids')) {
            // ✅ Cart selected
            $ids  = session('checkout_cart_ids', []);
            $cart = Cart::with('product')
                ->whereIn('id', $ids)
                ->where('user_id', $userId)
                ->get();
        } else {
            // ✅ Full cart
            $cart = Cart::with('product')->where('user_id', $userId)->get();
        }

        // ✅ Gunakan snapshot harga/subtotal dari tabel cart
        $subtotal = $cart->reduce(function ($carry, $c) {
            $harga = (float) ($c->harga ?? $c->product?->harga ?? 0);
            return $carry + ($harga * (int) $c->jumlah);
        }, 0);

        $totalBerat = $cart->reduce(fn($carry, $c) =>
            $carry + (($c->product?->berat ?? 0) * $c->jumlah), 0
        );


        return view('view-menu-layout.checkout', compact(
            'cart','kurir','addresses','paymentMethods','subtotal','totalBerat'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_address_id'     => ['required','exists:user_addresses,id'],
            'kurir_id'            => ['nullable','exists:kurirs,id'],
            'payment_method_id'   => ['required','exists:payment_methods,id'],
            'catatan'             => ['nullable','string'],
            'return_to'           => ['nullable','string'],
            'voucher_subtotal_id' => ['nullable','exists:discounts,id'],
            'voucher_shipping_id' => ['nullable','exists:discounts,id'],
            'total'               => ['required','numeric'],
        ]);

        $user = Auth::user();
        $cart = collect();

        // ✅ Cek sumber checkout
        if (session()->has('checkout_buy_now')) {
            // Buy Now
            $buyNow = session('checkout_buy_now');
            $product = Product::find($buyNow['product_id']);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            }

            $dummy = new \stdClass();
            $dummy->product  = $product;
            $dummy->jumlah   = $buyNow['jumlah'];
            $dummy->harga    = $product->harga;
            $dummy->subtotal = $product->harga * $buyNow['jumlah'];

            $cart = collect([$dummy]);

        } elseif (session()->has('checkout_cart_ids')) {
            // Selected cart
            $ids = session('checkout_cart_ids', []);
            $cart = Cart::with('product')
                ->whereIn('id', $ids)
                ->where('user_id', $user->id)
                ->get();

        } else {
            // Full cart
            $cart = Cart::with('product')->where('user_id', $user->id)->get();
        }

        if ($cart->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 422);
        }

        // ✅ Hitung subtotal & berat
        $subtotal = $cart->reduce(function ($carry, $c) {
            $harga = (float) ($c->harga ?? $c->product?->harga ?? 0);
            return $carry + ($harga * (int) $c->jumlah);
        }, 0);

        $totalBerat = $cart->reduce(fn($carry, $c) =>
            $carry + (($c->product?->berat ?? 0) * $c->jumlah), 0
        );

        // ✅ Hitung ongkir
        $kurir = $request->kurir_id ? Kurir::find($request->kurir_id) : null;
        $shippingBase = $kurir?->price ?? 0;
        $beratKg = ceil($totalBerat / 1000);
        $shipping = $shippingBase * $beratKg;

        // ✅ Diskon subtotal
        $subtotalDiscount = 0;
        if ($request->voucher_subtotal_id) {
            $voucher = Discounts::find($request->voucher_subtotal_id);
            if ($voucher && $voucher->status == 1) {
                if ($voucher->discount_type === 'percent') {
                    $subtotalDiscount = floor($subtotal * ($voucher->value / 100));
                } elseif ($voucher->discount_type === 'nominal') {
                    $subtotalDiscount = min($subtotal, $voucher->value);
                }
            }
        }

        // ✅ Diskon ongkir
        $shippingDiscount = 0;
        $isFreeShipping = false;
        if ($request->voucher_shipping_id) {
            $voucher = Discounts::find($request->voucher_shipping_id);
            if ($voucher && $voucher->status == 1) {
                if ($voucher->discount_type === 'free_shipping') {
                    $isFreeShipping = true;
                    $shippingDiscount = $shipping;
                    $shipping = 0;
                } elseif ($voucher->discount_type === 'shipping_discount') {
                    $shippingDiscount = min($shipping, $voucher->value);
                    $shipping = max(0, $shipping - $shippingDiscount);
                }
            }
        }

        // ✅ Hitung total final
        $validTotal = max(0, ($subtotal - $subtotalDiscount) + $shipping);

        // Validasi total frontend vs backend
        if ((int)$request->total !== (int)$validTotal) {
            return response()->json([
                'success' => false,
                'message' => 'Total tidak valid.',
                'debug'   => [
                    'server_total'      => $validTotal,
                    'request_total'     => (int)$request->total,
                    'subtotal'          => $subtotal,
                    'subtotal_discount' => $subtotalDiscount,
                    'shipping'          => $shipping,
                    'shipping_discount' => $shippingDiscount,
                    'is_free_shipping'  => $isFreeShipping,
                ]
            ], 422);
        }

        // ✅ Simpan order
        $kode = 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        $paymentMethod = Payment_methods::findOrFail($request->payment_method_id);

        $orderData = [
            'kode_pesanan'        => $kode,
            'user_id'             => $user->id,
            'user_address_id'     => $request->user_address_id,
            'kurir_id'            => $kurir?->id,
            'payment_method_id'   => $paymentMethod->id,
            'subtotal'            => $subtotal,
            'shipping_cost'       => $shipping,
            'total'               => $validTotal,
            'catatan'             => $request->catatan,
            'payment_status'      => 'pending',
            'shipping_status'     => 'pending',
            'return_to'           => $request->return_to,
            'voucher_subtotal_id' => $request->voucher_subtotal_id,
            'voucher_shipping_id' => $request->voucher_shipping_id,
        ];

        if ($paymentMethod->code === 'qris') {
            $orderData['qris_source']     = 'stored';
            $orderData['qris_image_path'] = $paymentMethod->qris_image_path;
            $orderData['qris_payload']    = $paymentMethod->qris_payload;
        }

        $order = Order::create($orderData);

        // Tandai voucher subtotal sebagai used
        if ($order->voucher_subtotal_id) {
            VoucherClaim::where('user_id', $user->id)
                ->where('voucher_id', $order->voucher_subtotal_id)
                ->whereNull('used_at')
                ->update(['used_at' => now()]);
        }

        // Tandai voucher shipping sebagai used
        if ($order->voucher_shipping_id) {
            VoucherClaim::where('user_id', $user->id)
                ->where('voucher_id', $order->voucher_shipping_id)
                ->whereNull('used_at')
                ->update(['used_at' => now()]);
        }

        // Simpan items + kurangi stok
        foreach ($cart as $c) {
            if (!$c->product) continue;

            // buat item order
            $order->items()->create([
                'product_id'  => $c->product->id,
                'nama_produk' => $c->product->nama_produk,
                'harga'       => $c->harga,
                'jumlah'      => $c->jumlah,
                'subtotal'    => $c->harga * $c->jumlah,
            ]);

            // ✅ kurangi stok langsung
            $c->product->decrement('stok', $c->jumlah);
        }

        // ✅ bersihkan session supaya buy now/cart gak nyangkut
        session()->forget(['checkout_buy_now','checkout_cart_ids']);


        return response()->json([
            'success'        => true,
            'order_id'       => $order->id,
            'kode_pesanan'   => $order->kode_pesanan,
            'total'          => $order->total,
            'payment_method' => $paymentMethod->name,
            'qris_image'     => $order->qris_image_path,
            'qris_payload'   => $order->qris_payload,
        ]);
    }



    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => ['required','exists:orders,id'],
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $paymentMethod = $order->paymentMethod;

        if (!$paymentMethod) {
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran tidak ditemukan untuk order ini.'
            ], 422);
        }

        if ($paymentMethod->code === 'qris') {
            $qrisImage = $order->qris_image_path
                ? asset('storage/'.$order->qris_image_path)
                : ($paymentMethod->qris_image_path ? asset('storage/'.$paymentMethod->qris_image_path) : null);

            $qrisPayload = $order->qris_payload ?? $paymentMethod->qris_payload ?? null;

            return response()->json([
                'success'        => true,
                'kode_pesanan'   => $order->kode_pesanan,
                'total'          => $order->total,
                'method'         => $paymentMethod->code,
                'qris_image_url' => $qrisImage,
                'qris_payload'   => $qrisPayload,
            ]);
        }

        // selain qris → instruksi manual (misalnya nomor rekening / e-wallet)
        return response()->json([
            'success'       => true,
            'kode_pesanan'  => $order->kode_pesanan,
            'total'         => $order->total,
            'method'        => $paymentMethod->code,
            'account_number'=> $paymentMethod->account_number ?? '-', // <<-- nomor rekening / ewallet
            'instructions'  => $paymentMethod->description ?? 'Silakan lakukan pembayaran sesuai metode ini.',
        ]);
    }

    public function uploadProof(Request $request)
    {
        $request->validate([
            'order_id' => ['required','exists:orders,id'],
            'proof'    => ['required', File::image()->max(5 * 1024)],
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($order->payment_status, ['pending','waiting_confirmation'])) {
            return response()->json(['success'=>false, 'message'=>'Order tidak bisa diunggah bukti.'], 422);
        }

        $path = $request->file('proof')->store('payment_proofs', 'public');

        $order->update([
            'proof_path'        => $path,
            'proof_submitted_at'=> now(),
            'payment_status'    => 'waiting_confirmation',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi.',
            'redirect_to' => $order->return_to ?: route('profile')
        ]);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'order_id' => ['required','exists:orders,id']
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order sudah dibayar.'
            ], 422);
        }

        // ✅ kembalikan stok produk dari order_items (baik Cart maupun Buy Now)
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stok', $item->jumlah);
            }
        }

        // ✅ update status order
        $order->update([
            'payment_status'  => 'cancelled',
            'shipping_status' => 'cancelled',
            'cancelled_at'    => now()
        ]);

        // ✅ bersihkan session checkout
        session()->forget(['checkout_buy_now', 'checkout_cart_ids']);

        return response()->json([
            'success' => true
        ]);
    }





}
