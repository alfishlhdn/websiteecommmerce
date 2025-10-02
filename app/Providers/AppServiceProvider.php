<?php

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Models\Store_Setiing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot()
    {
        // // Kalau APP_URL pakai https, paksa semua route jadi https
        // if (str_starts_with(config('app.url'), 'https://')) {
        //     URL::forceScheme('https');
        // }

        View::composer('*', function ($view) {
            $user = Auth::user();

            $store_settings = Store_Setiing::all();
            $wishlistItems = collect();
            $cartItems     = collect();
            $wishlistCount = 0;
            $cartCount     = 0; // total item (sum jumlah)
            $cartTotal     = 0; // total rupiah

            if ($user) {
                $wishlistItems = $user->wishlists()->with('product')->get();
                $cartItems = $user->cart()->with('product')->get();
                $wishlistCount = $wishlistItems->count();
                $cartCount     = $cartItems->count();

                $cartTotal = $cartItems->reduce(function ($sum, $i) {
                    $harga  = (int)($i->product->harga ?? 0);
                    $jumlah = (int)($i->jumlah ?? 0);
                    return $sum + ($harga * $jumlah);
                }, 0);
            }

            // 🚀 ambil produk untuk suggestion
            $allProducts = \App\Models\Product::select('id','nama_produk','slug')->limit(50)->get();

            $view->with(compact(
                'wishlistItems','cartItems','wishlistCount','cartCount','cartTotal','store_settings','allProducts'
            ));
        });
    }
}
