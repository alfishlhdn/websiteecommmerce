<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Categories;
// use Illuminate\Support\Carbon;
use App\Models\Visitor_Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil semua order 7 hari terakhir
        $orders = Order::where('created_at', '>=', Carbon::now()->subDays(6))
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d'); // grup per tanggal
            });

        // Siapkan label & data untuk chart
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::now()->subDays($i);
            $labels[] = $tgl->translatedFormat('d M');

            // jumlahkan total_harga untuk tanggal ini
            $total = isset($orders[$tgl->format('Y-m-d')])
                ? (float) $orders[$tgl->format('Y-m-d')]->sum('total')
                : 0;

            $data[] = $total;
        }

        return view('view-menu-dashboard.dashboard', [
            'totalProduk'      => Product::count(),
            'totalKategori'    => Categories::count(),
            'totalPesanan'     => Order::count(),
            'totalPembayaran' => Order::where('payment_status', 'paid')->sum('subtotal'),
            'totalPelanggan'   => User::where('role', 'user')->count(),
            'pesananTerbaru'   => Order::with(['user', 'items.product'])->latest()->take(5)->get(),
            'komentarTerbaru'  => Review::latest()->take(5)->get(),
            'chartLabels'      => array_values($labels),
            'chartData'        => array_values($data)
        ]);
    }


    public function analitik()
    {
        $today = Carbon::now()->startOfDay();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Data untuk grafik 7 hari terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $dates = [];
        $visitorCounts = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $count = Visitor_Log::whereDate('created_at', $date)
                ->select('ip_address', 'user_agent')->distinct()->count();
            $dates[] = $date->format('d/m');
            $visitorCounts[] = $count;
        }

        return view('view-menu-dashboard.analitik', [
            'totalVisitors'   => Visitor_Log::select('ip_address', 'user_agent')->distinct()->count(),
            'todayVisitors'   => Visitor_Log::whereDate('created_at', $today)
                                            ->select('ip_address', 'user_agent')->distinct()->count(),
            'weeklyVisitors'  => Visitor_Log::where('created_at', '>=', $startOfWeek)
                                            ->select('ip_address', 'user_agent')->distinct()->count(),
            'monthlyVisitors' => Visitor_Log::where('created_at', '>=', $startOfMonth)
                                            ->select('ip_address', 'user_agent')->distinct()->count(),
            'chartLabels'     => $dates,
            'chartData'       => $visitorCounts
        ]);
    }


}
