<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function finish(Request $request)
    {
        $orderCode = $request->get('order_id');
        $order = Order::where('kode_pesanan',$orderCode)->first();
        return view('result', ['title'=>'Pembayaran Selesai','subtitle'=>'Pembayaran diterima. Menunggu konfirmasi webhook.','order'=>$order,'status'=>'finish','raw'=>$request->all()]);
    }

    public function unfinish(Request $request)
    {
        $order = Order::where('kode_pesanan',$request->get('order_id'))->first();
        return view('result', ['title'=>'Pembayaran Belum Selesai','subtitle'=>'Selesaikan pembayaran sesuai instruksi.','order'=>$order,'status'=>'unfinish','raw'=>$request->all()]);
    }

    public function error(Request $request)
    {
        $order = Order::where('kode_pesanan',$request->get('order_id'))->first();
        return view('result', ['title'=>'Terjadi Kesalahan Pembayaran','subtitle'=>'Terjadi error saat pembayaran.','order'=>$order,'status'=>'error','raw'=>$request->all()]);
    }

    // Webhook / notification (POST) - set URL ini di Midtrans dashboard
    public function notification(Request $request)
    {
        $notif = new Notification();

        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status; // settlement, pending, capture, deny, cancel, expire, failure
        $paymentType = $notif->payment_type ?? null;
        $fraud = $notif->fraud_status ?? null;
        $transactionId = $notif->transaction_id ?? null;

        $order = Order::where('kode_pesanan', $orderId)->first();
        if (!$order) return response()->json(['message'=>'order not found'], 404);

        $internal = match ($transactionStatus) {
            'capture', 'settlement' => 'diproses',
            'pending' => 'pending',
            'deny', 'cancel', 'failure' => 'dibatalkan',
            'expire' => 'dibatalkan',
            default => 'pending',
        };

        $order->update([
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
            'fraud_status' => $fraud,
            'transaction_id' => $transactionId,
            'status' => $internal,
            'va_numbers' => $notif->va_numbers ?? null,
            'pdf_url' => $notif->pdf_url ?? null,
        ]);

        return response()->json(['message'=>'ok']);
    }
}