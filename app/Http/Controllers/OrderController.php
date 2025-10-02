<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store_Setiing;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'paymentMethod', 'kurir']);

        // Filter Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter Shipping Status
        if ($request->filled('shipping_status')) {
            $query->where('shipping_status', $request->shipping_status);
        }

        // Search (kode pesanan, nama user)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pesanan', 'like', "%$search%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                });
            });
        }

        // 🚀 Utamakan yang waiting_confirmation muncul di atas
        $orders = $query->orderByRaw("CASE WHEN payment_status = 'waiting_confirmation' THEN 0 ELSE 1 END")
                        ->latest()
                        ->paginate(10);

        return view('view-menu-dashboard.pesanan', compact('orders'));
    }

    public function detail($kode_pesanan)
    {
        $order = Order::with(['user', 'items.product', 'paymentMethod', 'kurir','address'])
            ->where('kode_pesanan', $kode_pesanan)
            ->firstOrFail();

        return response()->json($order);
    }


    public function update(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        $validated = $request->validate([
            'payment_status'   => 'required|in:pending,waiting_confirmation,paid,cancelled',
            'shipping_status'  => 'required|in:pending,processing,shipped,delivered,cancelled',
            'nomor_resi'       => 'nullable|string|max:255',
        ]);

        // ✅ Nomor nomor_resi hanya wajib kalau status shipping = shipped/delivered
        if (in_array($validated['shipping_status'], ['shipped', 'delivered']) && empty($validated['nomor_resi'])) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Resi wajib diisi jika status pengiriman Shipped atau Delivered'
            ], 422);
        }

        // ✅ kalau status jadi cancelled → kembalikan stok
        if (
            ($order->payment_status !== 'cancelled' && $validated['payment_status'] === 'cancelled')
            || ($order->shipping_status !== 'cancelled' && $validated['shipping_status'] === 'cancelled')
        ) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->jumlah);
                }
            }
        }

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil diperbarui'
        ]);
    }



    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order berhasil dihapus']);
    }


    public function invoice($id)
    {
        try {
            $order = Order::with([
                'items.product',   // relasi order_items → product
                'user',            // relasi order → user
                'kurir',           // relasi order → kurir
                'address'          // relasi order → address (jika ada)
            ])->findOrFail($id);

            $settings = Store_Setiing::first(); // ambil 1 toko saja

        return response()->json([
            'order' => [
                'id'           => $order->id,
                'kode_pesanan' => $order->kode_pesanan,
                'total'        => $order->total,
                'nomor_resi'   => $order->nomor_resi,
                'created_at'   => $order->created_at,
                'catatan'      => $order->catatan,
                'kurir' => $order->kurir ? [
                    'name' => $order->kurir->name
                ] : null,
                'user' => [
                    'name'    => $order->user->name ?? '-',
                ],
                'address' => $order->address ? [
                    'alamat_lengkap' => $order->address->alamat_lengkap ?? '-',
                    'provinsi' => $order->address->provinsi ?? '-',
                    'kota' => $order->address->kota ?? '-',
                    'telepon' => $order->address->telepon ?? '-',
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name'  => $item->product->nama_produk ?? '-',
                        'product_berat' => $item->product->berat ?? 0,
                        'quantity'      => $item->jumlah,
                        'price'         => $item->harga,
                    ];
                })->values(),
            ],
            'settings' => $settings ? [
                'store_name' => $settings->store_name,
                'address'    => $settings->address,
                'phone'      => $settings->phone,
            ] : null,
        ]);


        } catch (\Throwable $e) {
            // Log biar ketahuan error detail di laravel.log
            \Log::error("❌ Invoice error for order {$id}: " . $e->getMessage());

            return response()->json([
                'error'   => true,
                'message' => "Gagal memuat invoice: " . $e->getMessage(),
            ], 500);
        }
    }


}
