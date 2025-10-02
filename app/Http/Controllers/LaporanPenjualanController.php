<?php

// app/Http/Controllers/LaporanPenjualanController.php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal filter, default hari ini
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data order sesuai filter
        $orders = Order::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total penjualan
        $totalPenjualan = $orders->sum('total');

        return view('view-menu-dashboard.laporanpenjualan', [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPenjualan' => $totalPenjualan
        ]);
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        return Excel::download(new LaporanExport($startDate, $endDate), "laporan-penjualan.xlsx");
    }
}
