<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Whislist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Whislist::with('user', 'product')->latest()->get();
        return view('view-menu-dashboard.whilist', compact('wishlists'));
    }

    public function destroy(Whislist $wishlist)
    {
        $wishlist->delete();
        return redirect()->back()->with('success', 'Wishlist berhasil dihapus.');
    }

    public function indexlayout()
    {
        $userId = Auth::id();

        if (! $userId) {
            // middleware('auth') seharusnya melindungi ini, tapi jaga-jaga
            return redirect()->route('Masuk');
        }

        // Ambil wishlist user (eager load product) — Urut berdasarkan terbaru
        $wishlists = Whislist::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Produk rekomendasi (aktif, random, limit 10)
        $rekomendasiProduk = Product::where('status', 'aktif')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('view-menu-layout.wishlist', compact('wishlists', 'rekomendasiProduk'));
    }

    // Tambahkan wishlist yang dipilih ke keranjang (post)
    public function addToCart(Request $request)
    {
        $userId = Auth::id();
        $ids = $request->input('ids', []); // array of wishlist ids

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada item yang dipilih'], 422);
        }

        $hasil = [];

        foreach ($ids as $wishId) {
            try {
                $wish = Whislist::where('id', $wishId)
                    ->where('user_id', $userId)
                    ->with('product')
                    ->first();

                if (!$wish || !$wish->product) {
                    $hasil[$wishId] = [
                        'status'  => 'gagal',
                        'message' => 'Wishlist tidak ditemukan atau produk tidak tersedia.'
                    ];
                    continue;
                }

                $produk = $wish->product;
                $stok   = (int) $produk->stok;

                // ✅ validasi harga & status
                if ($produk->harga <= 0) {
                    $hasil[$wishId] = [
                        'status'  => 'gagal',
                        'message' => 'Produk tidak valid (harga belum diatur).',
                        'produk'  => $produk->nama_produk
                    ];
                    continue;
                }

                if (isset($produk->status) && $produk->status !== 'aktif') {
                    $hasil[$wishId] = [
                        'status'  => 'gagal',
                        'message' => 'Produk tidak aktif atau tidak bisa dibeli.',
                        'produk'  => $produk->nama_produk
                    ];
                    continue;
                }

                // cek apakah produk sudah ada di keranjang
                $itemKeranjang = Cart::where('user_id', $userId)
                    ->where('product_id', $produk->id)
                    ->first();

                $sudahDiKeranjang = $itemKeranjang ? (int) $itemKeranjang->jumlah : 0;
                $inginDitambah    = 1; // default 1
                $maksTambah       = max(0, $stok - $sudahDiKeranjang);

                if ($maksTambah <= 0) {
                    $hasil[$wishId] = [
                        'status'  => 'stok_habis',
                        'message' => 'Stok produk habis atau jumlah di keranjang sudah maksimal.',
                        'produk'  => $produk->nama_produk
                    ];
                    continue;
                }

                $ditambah = min($inginDitambah, $maksTambah);

                if ($itemKeranjang) {
                    $itemKeranjang->increment('jumlah', $ditambah);
                } else {
                    Cart::create([
                        'user_id'    => $userId,
                        'product_id' => $produk->id,
                        'harga'      => $produk->harga, // ✅ simpan harga
                        'jumlah'     => $ditambah,
                        'source'     => 'shop' // ✅ tandai asal dari shop
                    ]);
                }

                $hasil[$wishId] = [
                    'status'   => $ditambah < $inginDitambah ? 'ditambah_sebagian' : 'berhasil',
                    'message'  => $ditambah < $inginDitambah
                        ? "Produk hanya bisa ditambahkan {$ditambah} buah karena stok terbatas."
                        : "Produk berhasil ditambahkan ke keranjang.",
                    'produk'   => $produk->nama_produk,
                    'ditambah' => $ditambah,
                    'stok'     => $stok,
                    'source'   => 'shop'
                ];

            } catch (\Exception $e) {
                $hasil[$wishId] = [
                    'status'  => 'error',
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'message' => 'Proses selesai.',
            'hasil'   => $hasil
        ]);
    }



    // Hapus wishlist yang dipilih
    public function deleteSelected(Request $request)
    {
        $userId = Auth::id();
        $ids = $request->input('ids', []);

        if (! $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada item yang dipilih'], 422);
        }

        Whislist::whereIn('id', $ids)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'Item wishlist berhasil dihapus']);
    }



    public function destroydinavbar(Whislist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);
        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
