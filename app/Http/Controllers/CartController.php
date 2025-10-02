<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('user', 'product')->get();
        return view('view-menu-dashboard.cart', compact('carts'));
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function indexlayout()
    {
        $userId = Auth::id();

        // Jika belum Masuk, middleware('auth') seharusnya handle ini.
        if (! $userId) {
            return redirect()->route('Masuk');
        }

        // Ambil keranjang milik user, eager load product relation
        $carts = Cart::with('product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Rekomendasi produk
        $rekomendasiProduk = Product::where('status', 'aktif')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('view-menu-layout.keranjang', compact('carts', 'rekomendasiProduk'));
    }

    // Tambah item ke keranjang (bisa dipanggil saat Add to Cart)
    // body: product_id, qty (optional), variant_id (optional), note (optional)
    public function store(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) return response()->json(['success'=>'Unauthorized'], 401);

        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|integer',
            'note' => 'nullable|string|max:255',
        ]);

        $qty = $data['qty'] ?? 1;

        $product = Product::find($data['product_id']);
        if (! $product) return response()->json(['success'=>'Produk tidak ditemukan'], 404);

        // Cek stok produk / varian (asumsi kolom 'stok' di products, dan jika varian ada, cek variant->stok)
        $available = $product->stok ?? 0;
        if (! empty($data['variant_id'])) {
            // asumsi relation product->variants dan model variant punya 'stok'
            $variant = $product->variants()->where('id',$data['variant_id'])->first();
            if (! $variant) return response()->json(['success'=>'Varian tidak ditemukan'], 404);
            $available = $variant->stok ?? 0;
        }

        if ($qty > $available) {
            return response()->json(['success' => 'Stok tidak cukup', 'stockExceeded' => true, 'available' => $available], 422);
        }

        // Jika sudah ada cart untuk user + product (+variant), tambahkan qty, else buat baru
        $cartQuery = Cart::where('user_id', $userId)->where('product_id', $product->id);
        if (! empty($data['variant_id'])) {
            $cartQuery->where('variant_id', $data['variant_id']);
        } else {
            $cartQuery->whereNull('variant_id');
        }

        $cart = $cartQuery->first();

        if ($cart) {
            $newQty = $cart->jumlah + $qty;
            if ($newQty > $available) {
                return response()->json(['success'=>'Stok tidak cukup untuk menambah jumlah', 'stockExceeded'=>true, 'available'=>$available], 422);
            }
            $cart->jumlah = $newQty;
            $cart->note = $data['note'] ?? $cart->note;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'variant_id' => $data['variant_id'] ?? null,
                'jumlah' => $qty,
                'note' => $data['note'] ?? null,
            ]);
        }

        return response()->json(['success'=>'Berhasil ditambahkan ke keranjang', 'cart_id' => $cart->id]);
    }

    // Update qty (body: id, jumlah)
    public function updateQty(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) {
            return response()->json(['success' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'id'     => 'required|integer|exists:carts,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = Cart::with('product')
            ->where('id', $data['id'])
            ->where('user_id', $userId)
            ->first();

        if (! $cart) {
            return response()->json(['success' => 'Item tidak ditemukan'], 404);
        }

        $product = $cart->product;
        if (! $product) {
            return response()->json(['success' => 'Produk tidak tersedia lagi'], 404);
        }

        // cek stok
        $available = $product->stok ?? 0;
        if ($cart->variant_id) {
            $variant = $product->variants()->where('id', $cart->variant_id)->first();
            if ($variant) $available = $variant->stok ?? $available;
        }

        if ($data['jumlah'] > $available) {
            return response()->json([
                'success'       => 'Jumlah melebihi stok tersedia',
                'stockExceeded' => true,
                'available'     => $available
            ], 422);
        }

        // update qty
        $cart->jumlah = $data['jumlah'];
        $cart->save();

        // ✅ hitung subtotal & total pakai snapshot harga di cart
        $subtotal = $cart->harga * $cart->jumlah;

        $total = Cart::where('user_id', $userId)->sum(\DB::raw('harga * jumlah'));

        return response()->json([
            'success'  => 'Qty diperbarui',
            'subtotal' => $subtotal,
            'total'    => $total
        ]);
    }


    // Hapus selected (ids array)
    public function deleteSelected(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) return response()->json(['success'=>'Unauthorized'], 401);

        $ids = $request->input('ids', []);
        if (! is_array($ids) || empty($ids)) {
            return response()->json(['success'=>'Tidak ada item dipilih'], 422);
        }

        Cart::whereIn('id', $ids)->where('user_id', $userId)->delete();

        return response()->json(['success'=>'Item berhasil dihapus']);
    }

    public function checkoutSingle(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) {
            return response()->json(['success' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'id' => 'required|integer|exists:carts,id',
        ]);

        $cart = Cart::with('product')->where('id', $data['id'])->where('user_id', $userId)->first();
        if (! $cart) {
            return response()->json(['success' => 'Item tidak ditemukan'], 404);
        }

        $product = $cart->product;
        if (! $product) {
            return response()->json(['success' => 'Produk tidak tersedia'], 404);
        }

        $available = $product->stok ?? 0;
        if ($cart->variant_id) {
            $variant = $product->variants()->where('id', $cart->variant_id)->first();
            if (! $variant) {
                return response()->json(['success' => 'Varian tidak ditemukan'], 422);
            }
            $available = $variant->stok ?? $available;
        }

        if ($cart->jumlah > $available) {
            return response()->json([
                'success' => 'Jumlah melebihi stok tersedia',
                'stockExceeded' => true,
                'available' => $available
            ], 422);
        }

        // 🔥 Reset session buy now kalau user checkout dari cart
        session()->forget('checkout_buy_now');

        // Simpan satu cart id ke session untuk checkout
        session(['checkout_cart_ids' => [$cart->id]]);

        return response()->json([
            'success' => 'Siap checkout item ini',
            'redirect' => route('checkout') // pastikan route('checkout') ada
        ]);
    }

    public function checkoutSelected(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) {
            return response()->json(['success' => 'Unauthorized'], 401);
        }

        $ids = $request->input('ids', []);
        if (! is_array($ids) || empty($ids)) {
            return response()->json(['success' => 'Tidak ada item dipilih'], 422);
        }

        // Ambil carts dan eager load product & variants
        $carts = Cart::with('product')->whereIn('id', $ids)->where('user_id', $userId)->get();

        if ($carts->count() === 0) {
            return response()->json(['success' => 'Item tidak ditemukan'], 404);
        }

        $problems = [];

        foreach ($carts as $cart) {
            $product = $cart->product;
            if (! $product) {
                $problems[] = [
                    'cart_id' => $cart->id,
                    'success' => 'Produk tidak tersedia',
                ];
                continue;
            }

            $available = $product->stok ?? 0;
            // jika ada variant_id, cek variant stok (asumsi relation variants)
            if ($cart->variant_id) {
                $variant = $product->variants()->where('id', $cart->variant_id)->first();
                if (! $variant) {
                    $problems[] = [
                        'cart_id' => $cart->id,
                        'success' => 'Varian tidak ditemukan',
                    ];
                    continue;
                }
                $available = $variant->stok ?? $available;
            }

            if ($cart->jumlah > $available) {
                $problems[] = [
                    'cart_id' => $cart->id,
                    'success' => 'Stok tidak cukup',
                    'available' => $available,
                ];
            }
        }

        if (! empty($problems)) {
            // kembalikan detail supaya client bisa memberi tahu user
            return response()->json([
                'success' => 'Beberapa item tidak tersedia dengan jumlah yang diminta',
                'problems' => $problems
            ], 422);
        }

         // 🔥 Reset session buy now kalau user checkout dari cart
        session()->forget('checkout_buy_now');

        // Simpan cart ids yang dipilih ke session agar proses checkout dapat menggunakan ini
        session(['checkout_cart_ids' => $carts->pluck('id')->toArray()]);

        // kembalikan redirect ke route checkout (ubah sesuai route checkout aplikasi)
        return response()->json([
            'success' => 'Siap checkout',
            'redirect' => route('checkout.index') // pastikan route('checkout') ada
        ]);
    }

    public function destroydinavbar(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        $cart->delete();

        return back()->with('success', 'Item keranjang dihapus.');
    }



}
