<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $limitKategori = 5; // bisa diganti sesuai kebutuhan
        $kategori = Categories::limit($limitKategori + 1)->get();
        $rekomendasiProduk = Product::query()
            ->where('status', 'aktif')
            ->withAvg(['reviews as rating' => function ($q) {
                $q->where('status', 'disetujui');
            }], 'rating')
            ->withCount(['reviews as ulasan_count' => function ($q) {
                $q->where('status', 'disetujui');
            }])
            ->withSum(['orderItems as terjual' => function ($q) {
                $q->whereHas('order', function ($o) {
                    $o->where('payment_status', 'paid')
                    ->where('shipping_status', 'delivered');
                });
            }], 'jumlah')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $produkTerbaru = Product::query()
            ->where('status', 'aktif')
            ->withAvg(['reviews as rating' => function ($q) {
                $q->where('status', 'disetujui');
            }], 'rating')
            ->withCount(['reviews as ulasan_count' => function ($q) {
                $q->where('status', 'disetujui');
            }])
            ->withSum(['orderItems as terjual' => function ($q) {
                $q->whereHas('order', function ($o) {
                    $o->where('payment_status', 'paid')
                    ->where('shipping_status', 'delivered');
                });
            }], 'jumlah')
            ->latest()
            ->limit(10)
            ->get();

        return view('view-menu-layout.Beranda', compact(
            'kategori','rekomendasiProduk','produkTerbaru','limitKategori'
        ));
    }
}



// $response = Http::get('https://api-berita-indonesia.vercel.app/cnn/teknologi');
// // Pastikan ambil array data (bukan string)
// if ($response->successful()) {
// $data = $response->json(); // Ambil semua JSON response
// $artikel = $data['data']['posts'] ?? []; // Ambil key 'posts'
// } else {
//     $artikel = []; // Jika API gagal, fallback array kosong
// }
