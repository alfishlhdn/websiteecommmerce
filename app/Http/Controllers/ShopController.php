<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Brand;
use App\Models\Whislist;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Tampilkan halaman shop dengan filter, sort, pagination
     * NOTE: filter 'category' and 'brand' expect slug values in the URL/query.
     */
    public function index(Request $request)
    {
        $q = Product::query()
            ->where('status', '!=', 'draft')
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
            }], 'jumlah');

        // -------------------------
        // FILTER CATEGORY
        // -------------------------
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $category = Categories::where('slug', $categorySlug)->first();
            if ($category) {
                $q->where('category_id', $category->id);
            }
        }

        // -------------------------
        // FILTER BRAND
        // -------------------------
        if ($request->filled('brand')) {
            $brandSlug = $request->brand;
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $q->where('brand_id', $brand->id);
            }
        }

        // -------------------------
        // FILTER PRICE
        // -------------------------
        if ($request->filled('price_min')) {
            $q->where('harga', '>=', (int) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $q->where('harga', '<=', (int) $request->price_max);
        }

        // -------------------------
        // SEARCH
        // -------------------------
        if ($request->filled('search')) {
            $search = trim($request->search);

            $categoryBySlug = Categories::where('slug', $search)->first();
            $brandBySlug = Brand::where('slug', $search)->first();

            $q->where(function ($qq) use ($search, $categoryBySlug, $brandBySlug) {
                $qq->where('nama_produk', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');

                if ($categoryBySlug) {
                    $qq->orWhere('category_id', $categoryBySlug->id);
                }

                if ($brandBySlug) {
                    $qq->orWhere('brand_id', $brandBySlug->id);
                }
            });
        }

        // -------------------------
        // SORTING
        // -------------------------
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'latest':
                $q->orderBy('id','desc');
                break;
            case 'lowest':
                $q->orderBy('harga','asc');
                break;
            case 'highest':
                $q->orderBy('harga','desc');
                break;
            case 'popular':
                $q->orderBy('stok','desc');
                break;
            default:
                $q->orderBy('id','desc');
        }

        $perPage = 6;
        $products = $q->paginate($perPage)->appends($request->query());

        $categories = Categories::orderBy('nama_kategori')->get();
        $brands = Brand::orderBy('name')->get();

        return view('view-menu-layout.shop', compact('products','categories','brands'));
    }



/**
 * Toggle wishlist (unchanged except defensive product lookup)
 */
    public function toggleWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $v = Validator::make($request->all(), [
            'product_id' => 'required' // accept numeric id or slug
        ]);

        if ($v->fails()) {
            return response()->json(['error' => 'validation', 'messages' => $v->errors()], 422);
        }

        $input = $request->product_id;

        // try slug first, fallback to id
        $product = is_numeric($input) ? Product::find((int)$input) : Product::where('slug', $input)->first();
        if (!$product && !is_numeric($input) && ctype_digit($input)) {
            $product = Product::find((int)$input);
        }

        if (!$product) {
            return response()->json(['error' => 'product_not_found'], 422);
        }

        $productId = $product->id;
        $exists = Whislist::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed']);
        }

        Whislist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return response()->json(['status' => 'added']);
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $v = Validator::make($request->all(), [
            'product_id' => 'required', // id atau slug
            'jumlah'     => 'required|integer|min:1',
            'source'     => 'nullable|string|in:shop,pricelist'
        ]);

        if ($v->fails()) {
            return response()->json([
                'error' => 'validation',
                'messages' => $v->errors(),
            ], 422);
        }

        $input = $request->product_id;
        $product = is_numeric($input)
            ? Product::find((int)$input)
            : Product::where('slug', $input)->first();

        if (!$product && !is_numeric($input) && ctype_digit($input)) {
            $product = Product::find((int)$input);
        }

        if (!$product) {
            return response()->json(['error' => 'product_not_found'], 422);
        }

        $productId = $product->id;
        $qty       = (int) $request->jumlah;
        $stock     = (int) ($product->stok ?? 0);
        $source    = $request->get('source', 'shop');

        // total qty user untuk produk ini across all sources
        $totalInCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->sum('jumlah');

        if ($stock <= 0) {
            return response()->json([
                'error' => 'Stok Habis',
                'message' => 'Produk habis stok.',
                'available' => 0
            ], 422);
        }

        if ($totalInCart + $qty > $stock) {
            return response()->json([
                'error' => 'Stok Tidak Cukup',
                'message' => 'Jumlah total di cart (shop + pricelist) melebihi stok.',
                'available' => $stock,
                'current_total' => $totalInCart,
                'requested' => $qty
            ], 422);
        }

        // Tentukan harga snapshot
        $hargaSnapshot = (float) ($product->harga ?? 0);

        if ($source === 'pricelist') {
            // Harga dari tabel pricelists (tanpa diskon produk)
            $pl = \App\Models\Pricelist::where('product_id', $productId)->first();
            if ($pl && $pl->price) {
                $hargaSnapshot = (float) $pl->price;
            }
        } else {
            // Harga dari shop + apply diskon produk jika ada
            $discount = $product->discounts
                ->filter(function ($d) {
                    return $d->type === 'product'
                        && $d->status == 1
                        && (is_null($d->expired_at) || $d->expired_at >= now());
                })
                ->first();

            if ($discount) {
                if ($discount->discount_type === 'percent') {
                    $hargaSnapshot = $hargaSnapshot - ($hargaSnapshot * ($discount->value / 100));
                } elseif ($discount->discount_type === 'nominal') {
                    $hargaSnapshot = max(0, $hargaSnapshot - $discount->value);
                }
            }
        }

        // simpan cart berdasarkan user + product + source unik
        $cart = Cart::firstOrNew([
            'user_id'    => Auth::id(),
            'product_id' => $productId,
            'source'     => $source
        ]);

        $existingQty = $cart->exists ? (int)$cart->jumlah : 0;
        $newQty      = $existingQty + $qty;

        $cart->jumlah = $newQty;
        $cart->harga  = $hargaSnapshot; // selalu update harga snapshot
        $cart->save();

        return response()->json([
            'status' => 'ok',
            'jumlah' => $cart->jumlah,
            'harga'  => number_format($cart->harga, 0, ',', '.'),
            'source' => $cart->source
        ]);
    }

    public function checkStock(Request $request)
    {
        // optional: allow guest to check stock; if you want only logged users, uncomment:
        // if (!Auth::check()) return response()->json(['error'=>'unauthenticated'],401);

        $v = Validator::make($request->all(), [
            'product_id' => 'required',
            'jumlah' => 'required|integer|min:1'
        ], [
            'jumlah.min' => 'Jumlah minimal 1.'
        ]);

        if ($v->fails()) {
            return response()->json(['ok' => false, 'message' => $v->errors()->first(), 'errors'=>$v->errors()], 422);
        }

        $input = $request->product_id;
        $qty = (int)$request->jumlah;

        // PRIORITIZE slug then fallback id
        $product = Product::where('slug', $input)->first();
        if (!$product && is_numeric($input)) {
            $product = Product::find((int)$input);
        }
        if (!$product && !is_numeric($input) && ctype_digit($input)) {
            $product = Product::find((int)$input);
        }

        if (!$product) {
            return response()->json(['ok' => false, 'message' => 'Produk tidak ditemukan.'], 422);
        }

        $stock = (int) ($product->stok ?? 0);

        if ($stock <= 0) {
            return response()->json(['ok' => false, 'message' => 'Maaf, produk ini sedang habis.', 'available' => 0], 422);
        }

        if ($qty > $stock) {
            return response()->json([
                'ok' => false,
                'message' => "Jumlah yang diminta melebihi stok. Tersedia: {$stock}.",
                'available' => $stock
            ], 422);
        }

        // all good: return slug (so frontend dapat canonical slug) and available
        return response()->json(['ok' => true, 'available' => $stock, 'product_slug' => $product->slug, 'message' => 'Stok tersedia.']);
    }
}
