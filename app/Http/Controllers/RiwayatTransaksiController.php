<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class RiwayatTransaksiController extends Controller
{
    public function index(Request $r)
    {
        $query = Order::with(['items','kurir','user'])
            ->latest();

        // Filter status pengiriman
        if ($r->filled('shipping_status')) {
            $query->where('shipping_status', $r->shipping_status);
        }

        // Filter tanggal
        if ($r->filled('from')) {
            $query->whereDate('created_at','>=',$r->from);
        }

        if ($r->filled('to')) {
            $query->whereDate('created_at','<=',$r->to);
        }

        $orders = $query->paginate(20);

        return view('view-menu-dashboard.riwayat-transaksi', compact('orders'));
    }
}