<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\payments;
use App\Models\Order;
use App\Models\PaymentMethod;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = payments::with('order','method')->latest()->paginate(20);
        return view('view-menu-dashboard.pembayaran', compact('pembayaran'));
    }

    // Show detail and manual verify (admin)
    public function show($id)
    {
        $payment = payments::with('order','method')->findOrFail($id);
        return view('pembayaran.show', compact('payment'));
    }

    // Manual set status to paid (misal setelah cek bukti)
    public function verify(Request $r, $id)
    {
        $payment = payments::findOrFail($id);
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        $order = $payment->order;
        $order->status = 'diproses';
        $order->save();

        return back()->with('success','Pembayaran diverifikasi');
    }
}
