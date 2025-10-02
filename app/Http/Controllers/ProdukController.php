<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Review;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\PriceListExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product_Specification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class ProdukController extends Controller
{
    public function index()
    {
        $query = Product::with(['category', 'brand', 'images', 'specifications','discounts']);

        // Fitur Search
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('harga', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('nama_kategori', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('brand', function ($q3) use ($search) {
                        $q3->where('name', 'like', '%' . $search . '%');
                    });
            });
        }


        // Fitur Sort
        if (request('sort')) {
            switch (request('sort')) {
            case 'nama_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            case 'harga_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'status_aktif':
                $query->orderByRaw("CASE WHEN status = 'aktif' THEN 0 ELSE 1 END");
                break;
            case 'status_nonaktif':
                $query->orderByRaw("CASE WHEN status = 'nonaktif' THEN 0 ELSE 1 END");
                break;
            }

        } else {
            // Default sorting (misal berdasarkan ID terbaru)
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(10)->withQueryString(); // agar pagination tetap bawa query search & sort
        $categories = Categories::all();
        $brands = Brand::all();

        return view('view-menu-dashboard.produk', compact('products', 'categories', 'brands'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'brand_id' => 'required',
            'nama_produk' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'berat' => 'required|integer',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image',
            'specifications.*.key' => 'nullable|string',
            'specifications.*.value' => 'nullable|string',
            'images.*' => 'nullable|image',
        ]);

        $produk = new Product();
        $produk->category_id = $request->category_id;
        $produk->brand_id = $request->brand_id;
        $produk->nama_produk = $request->nama_produk;
        $produk->slug = Str::slug($request->nama_produk);
        $produk->deskripsi = $request->deskripsi;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->berat = $request->berat;
        $produk->status = $request->status;

        if ($request->hasFile('foto')) {
            $filename = $produk->slug . '.' . $request->file('foto')->extension();
            $path = $request->file('foto')->storeAs('public/produk', $filename);
            $produk->foto = $path;
        }

        $produk->save();

        // 💡 Simpan default price list untuk produk ini
        PriceList::create([
            'product_id' => $produk->id,
            'client_id' => null, // null artinya default untuk semua client
            'price' => $request->input('harga_b2b') ?? $produk->harga,
        ]);

        // Specifications
        if ($request->has('specifications')) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    Product_Specification::create([
                        'product_id' => $produk->id,
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        // Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $images) {
                $filename = $produk->slug . '-tambahan-' . ($index + 1) . '.' . $images->extension();
                $path = $images->storeAs('public/produk', $filename);

                Image::create([
                    'product_id' => $produk->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $produk = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'brand_id' => 'required',
            'nama_produk' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'berat' => 'required|integer',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image',
            'harga_b2b' => 'nullable|numeric',
            'min_qty' => 'nullable|integer|min:1',
        ]);

        $produk->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'status' => $request->status,
        ]);

        // update foto utama
        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::delete($produk->foto);
            }
            $filename = $produk->slug . '.' . $request->file('foto')->extension();
            $path = $request->file('foto')->storeAs('public/produk', $filename);
            $produk->foto = $path;
            $produk->save();
        }

        // sinkronisasi price list
        PriceList::updateOrCreate(
            ['product_id' => $produk->id, 'client_id' => null], // default client
            [
                'price' => $request->input('harga_b2b') ?? $produk->harga,
            ]
        );

        // update spesifikasi
        Product_Specification::where('product_id', $produk->id)->delete();
        if ($request->has('specifications')) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    Product_Specification::create([
                        'product_id' => $produk->id,
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        // tambah gambar tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $images) {
                $filename = $produk->slug . '-tambahan-' . time() . '-' . ($index + 1) . '.' . $images->extension();
                $path = $images->storeAs('public/produk', $filename);
                Image::create([
                    'product_id' => $produk->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Product::with('images', 'specifications')->findOrFail($id);

        if ($produk->foto) {
            Storage::delete($produk->foto);
        }

        foreach ($produk->images as $images) {
            Storage::delete($images->image_path);
            $images->delete();
        }

        $produk->specifications->each->delete();
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }

    public function deleteImage($id)
    {
        $image = Image::findOrFail($id);

        // Hapus file dari storage
        if (Storage::exists('public/produk/' . $image->image_path)) {
            Storage::delete('public/produk/' . $image->image_path);
        }

        // Hapus dari database
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    protected function resolveProduct($identifier)
    {
        if (!$identifier) {
            return null;
        }

        // try by slug first (prioritize slug even if numeric)
        $product = Product::where('slug', $identifier)->first();
        if ($product) {
            return $product;
        }

        // fallback: if looks like id (numeric) try find by id
        if (is_numeric($identifier)) {
            return Product::find((int)$identifier);
        }

        return null;
    }

    /**
     * Show product detail by slug or id (slug prioritized).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|int  $identifier
     */
    public function showdetail(Request $request, $identifier)
    {
        $product = $this->resolveProduct($identifier)
            ->loadSum(['orderItems as terjual' => function ($q) {
                $q->whereHas('order', function ($o) {
                    $o->where('payment_status', 'paid')
                    ->where('shipping_status', 'delivered');
                });
            }], 'jumlah');


        if (!$product) {
            abort(404);
        }

        // protection optional: jangan tampilkan draft
        if ($product->status === 'draft') {
            abort(404);
        }

        // eager load relations
        $product->load(['images', 'specifications', 'brand', 'category']);

        // reviews: paginate (6 per page)
        $reviews = $product->reviews()->with('user')->latest()->paginate(6);

        // average rating & total reviews
        $avgRating = round((float) $product->reviews()->avg('rating'), 1) ?: 0;
        $totalReviews = $product->reviews()->count();

        // rekomendasiProduk sederhana
        $rekomendasiProduk = Product::query()
        ->where('status', 'aktif')
        // rata-rata rating dari review yang disetujui
        ->withAvg(['reviews as rating' => function ($q) {
            $q->where('status', 'disetujui');
        }], 'rating')
        // jumlah ulasan (disetujui)
        ->withCount(['reviews as ulasan_count' => function ($q) {
            $q->where('status', 'disetujui');
        }])
        // total terjual = sum(jumlah) dari order_items yang order.status paid/completed
        ->withSum(['orderItems as terjual' => function ($q) {
            $q->whereHas('order', function ($o) {
                $o->where('payment_status', 'paid')
                ->where('shipping_status', 'delivered');
            });
        }], 'jumlah')
        ->inRandomOrder()
        ->limit(10)
        ->get();

        return view('view-menu-layout.detailproduk',compact('product','avgRating','rekomendasiProduk','totalReviews','reviews'));
    }

    /**
     * Store a review via AJAX.
     * Accepts product identifier in URL (slug or id), rating and komentar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|int  $identifier
     */
    public function storeReview(Request $request, $identifier)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $product = $this->resolveProduct($identifier);
        if (!$product) {
            return response()->json(['error' => 'product_not_found'], 404);
        }

        $v = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:3|max:2000'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => 'validation', 'messages' => $v->errors()], 422);
        }

        // Create review (sesuaikan field nama kolom di DB)
        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => (int) $request->rating,
            'komentar' => $request->komentar,
            'status' => 'disetujui' // jika kamu memakai workflow approve; ubah bila perlu
        ]);

        $review->load('user');

        // recalc avg and total
        $avgRating = round((float) $product->reviews()->avg('rating'), 1) ?: 0;
        $totalReviews = $product->reviews()->count();

        $created = $review->created_at ? $review->created_at->diffForHumans() : now()->diffForHumans();

        return response()->json([
            'status' => 'ok',
            'review' => [
                'id' => $review->id,
                'user_name' => $review->user->name ?? 'Anonim',
                'rating' => (int)$review->rating,
                'komentar' => $review->komentar,
                'created_at' => $created
            ],
            'avgRating' => $avgRating,
            'totalReviews' => $totalReviews
        ]);
    }

    public function buyNow(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'jumlah'     => 'required|integer|min:1',
            ]);

            $userId = Auth::id();
            if (! $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized (belum login)'], 401);
            }

            $product = Product::find($data['product_id']);
            if (! $product) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            }

            if ($product->stok < $data['jumlah']) {
                return response()->json([
                    'success'   => false,
                    'message'   => "Stok tidak mencukupi. Sisa stok: {$product->stok}",
                ], 422);
            }

                // 🔥 Reset session cart kalau user pakai Buy Now
            session()->forget('checkout_cart_ids');

            // simpan session sementara untuk checkout
            session(['checkout_buy_now' => [
                'product_id' => $product->id,
                'jumlah'        => $data['jumlah']
            ]]);

            return response()->json([
                'success'  => true,
                'redirect' => route('checkout.index')
            ]);
        } catch (\Throwable $e) {
            // log detail di storage/logs/laravel.log
            \Log::error('❌ Buy Now Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // kirim JSON detail ke FE
            return response()->json([
                'success' => false,
                'message' => "Server error: " . $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    public function priceList(Request $request)
    {
        // Mendapatkan semua kategori untuk dropdown filter
        $categories = Categories::all();

        // Memulai query dengan eager loading
        $query = Product::with(['priceList', 'specifications', 'images', 'category', 'brand']);

        // Filter berdasarkan pencarian (search)
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->input('search') . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Pengurutan (sorting)
        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'price_asc':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('nama_produk', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama_produk', 'desc');
                    break;
                default:
                    $query->latest(); // Default: terbaru
                    break;
            }
        } else {
            $query->latest(); // Default: urutkan berdasarkan yang terbaru
        }

        // Jalankan query dan terapkan paginasi
        $products = $query->paginate(10)->withQueryString();

        return view('view-menu-layout.pricelist', compact('products', 'categories'));
    }


    public function exportPriceList(Request $request)
    {
        $query = Product::with(['priceList', 'category', 'brand']);

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'price_asc':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('nama_produk', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama_produk', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->get();

        return Excel::download(new PriceListExport($products), 'price_list.xlsx');
    }
}
