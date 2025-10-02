<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\payments;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans config
        // Ganti dengan credentials sandbox/production di .env
        Config::$serverKey = config('services.midtrans.server_key'); // set di .env
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production') ? true : false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

public function pay(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id'
    ]);

    $order = Order::with('items', 'user', 'kurir')->findOrFail($request->order_id);

    // build payload item_details
    $items = [];
    foreach ($order->items as $it) {
        $items[] = [
            'id' => $it->product_id,
            'price' => (float) $it->harga_satuan,
            'quantity' => (int) $it->jumlah,
            'name' => $it->product->nama ?? 'Product_'.$it->product_id
        ];
    }

    if ($order->kurir) {
        $items[] = [
            'id' => 'shipping',
            'price' => (float) $order->kurir->price,
            'quantity' => 1,
            'name' => 'Ongkos Kirim - '.$order->kurir->name
        ];
    }

    $params = [
        'transaction_details' => [
            'order_id' => $order->kode_pesanan,
            'gross_amount' => (float) $order->total_harga,
        ],
        'item_details' => $items,
        'customer_details' => [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? null,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);

        // simpan payment record
        $payment = payments::create([
            'order_id' => $order->id,
            'payment_method_id' => 1, // snap / qris
            'midtrans_order_id' => $params['transaction_details']['order_id'],
            'amount' => $order->total_harga,
            'status' => 'pending',
            'raw_response' => json_encode(['snap_token' => $snapToken])
        ]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'payment_id' => $payment->id
        ]);
    } catch (\Exception $e) {
        Log::error('Midtrans error: '.$e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

}
