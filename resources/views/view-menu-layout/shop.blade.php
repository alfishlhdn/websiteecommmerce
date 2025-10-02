@extends('Layouts.main')

@section('judul', 'Shop | AGA IT COMPUTER')

@section('content')
    <section class="max-w-7xl mx-auto px-4 py-12 md:py-16 rounded-lg shadow-inner">
        <!-- Breadcrumb -->
        <nav class="text-xs sm:text-sm font-medium text-gray-500 mb-4 sm:mb-6">
            <a href="/" class="hover:text-blue-600 transition">Home</a>
            <span class="mx-1 sm:mx-2">/</span>
            <span class="text-gray-800">Shop</span>
        </nav>


        <div class="grid grid-cols-12 gap-6 md:gap-10">
            <!-- Sidebar Filter (desktop) -->
            <aside
                class="hidden md:block col-span-12 md:col-span-3 space-y-6 bg-white p-6 rounded-xl shadow-lg h-fit sticky top-8">
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-3 border-b-2 border-blue-600 pb-2">Filter
                    Produk ⚙️</h2>
                <form id="filter-form" action="{{ route('shop.index') }}" method="GET" class="space-y-6">
                    <!-- Search -->
                    <div class="space-y-2">
                        <label for="input-search" class="text-sm font-semibold text-gray-700 block">Cari Produk</label>
                        <div class="relative">
                            <input id="input-search" name="search" value="{{ request('search') }}" type="text"
                                placeholder="Cari produk..."
                                class="w-full p-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="filter-category" class="text-sm font-semibold text-gray-700 block">Kategori</label>
                        <select id="filter-category" name="category"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}"
                                    {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Brand -->
                    <div class="space-y-2">
                        <label for="filter-brand" class="text-sm font-semibold text-gray-700 block">Brand</label>
                        <select id="filter-brand" name="brand"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">Semua Brand</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Price -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 block">Harga</label>
                        <div class="flex gap-4">
                            <input name="price_min" value="{{ request('price_min') }}" type="number" placeholder="Min"
                                class="w-1/2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <input name="price_max" value="{{ request('price_max') }}" type="number" placeholder="Max"
                                class="w-1/2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div>
                        <button type="submit"
                            class="mt-4 w-full text-sm bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-md">Terapkan
                            Filter</button>
                        <a href="{{ route('shop.index') }}"
                            class="mt-2 block text-center text-sm bg-gray-200 hover:bg-gray-300 py-3 rounded-lg font-medium">Reset
                            Filter</a>
                    </div>
                </form>
            </aside>


            <!-- Sidebar Filter (mobile toggle) -->
            <div class="col-span-12 md:hidden">
                <button onclick="document.getElementById('mobile-filter').classList.toggle('hidden')"
                    class="w-full mb-4 px-4 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700">
                    🔍 Tampilkan Filter
                </button>
                <div id="mobile-filter" class="hidden bg-white p-4 rounded-xl shadow-lg space-y-6">
                    <form action="{{ route('shop.index') }}" method="GET" class="space-y-6">
                        <!-- Search, Category, Brand, Price sama persis dengan desktop -->
                        <div class="space-y-2">
                            <label for="input-search-m" class="text-sm font-semibold text-gray-700 block">Cari
                                Produk</label>
                            <div class="relative">
                                <input id="input-search-m" name="search" value="{{ request('search') }}" type="text"
                                    placeholder="Cari produk..."
                                    class="w-full p-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Category -->
                        <div class="space-y-2">
                            <label for="filter-category" class="text-sm font-semibold text-gray-700 block">Kategori</label>
                            <select id="filter-category" name="category"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm transition">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->slug }}"
                                        {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Brand -->
                        <div class="space-y-2">
                            <label for="filter-brand" class="text-sm font-semibold text-gray-700 block">Brand</label>
                            <select id="filter-brand" name="brand"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm transition">
                                <option value="">Semua Brand</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->slug }}"
                                        {{ request('brand') == $b->slug ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Price -->
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 block">Harga</label>
                            <div class="flex gap-4">
                                <input name="price_min" value="{{ request('price_min') }}" type="number"
                                    placeholder="Min"
                                    class="w-1/2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <input name="price_max" value="{{ request('price_max') }}" type="number"
                                    placeholder="Max"
                                    class="w-1/2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            </div>
                        </div>
                        <button type="submit"
                            class="mt-4 w-full text-sm bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-md">Terapkan
                            Filter</button>
                        <a href="{{ route('shop.index') }}"
                            class="mt-2 block text-center text-sm bg-gray-200 hover:bg-gray-300 py-3 rounded-lg font-medium">Reset
                            Filter</a>
                    </form>
                </div>
            </div>


            <!-- Produk -->
            <div class="col-span-12 md:col-span-9 space-y-8">
                <!-- Title + Sort -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3 sm:mb-0">Produk Kami ✨</h1>
                    <form id="sort-form" action="{{ route('shop.index') }}" method="GET"
                        class="flex items-center w-full sm:w-auto space-x-2">
                        @foreach (request()->except('sort', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label for="sort-select"
                            class="hidden md:block text-sm font-medium text-gray-700">Urutkan:</label>
                        <select name="sort" id="sort-select"
                            class="flex-1 sm:flex-none border border-gray-300 p-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                            onchange="this.form.submit()">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Urutkan</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Harga Terendah
                            </option>
                            <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Harga Tertinggi
                            </option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Laris
                            </option>
                        </select>
                    </form>
                </div>


                <!-- Produk Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6 md:gap-8">
                    @forelse($products as $product)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden group transition hover:scale-105 hover:shadow-xl relative">
                            @if ($product->is_new)
                                <span
                                    class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">Baru</span>
                            @endif
                            <a href="{{ route('produk.showdetail', $product->slug) }}">
                                <div class="w-full h-40 sm:h-48 md:h-56 bg-gray-100 overflow-hidden relative">
                                    <img src="{{ asset(Storage::url($product->foto)) }}"
                                        alt="{{ $product->nama_produk }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                            </a>

                            <button data-product-id="{{ $product->id }}"
                                class="btn-wishlist absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:text-red-500 transition-colors duration-300 z-10">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <div class="p-4 space-y-2">
                                <h3 class="text-base font-bold text-gray-800">
                                    <a href="{{ route('produk.showdetail', $product->slug) }}"
                                        class="hover:text-blue-600 transition duration-300">
                                        {{ Str::limit($product->nama_produk, 30) }}
                                    </a>

                                </h3>

                                <div class="flex items-center gap-2">
                                    <div class="text-yellow-400 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        {{ number_format($product->rating ?? 0, 1) }}
                                    </div>
                                    <div class="text-gray-500 text-xs">({{ $product->ulasan_count ?? 0 }} ulasan)</div>
                                </div>

                                <p class="text-sm font-medium text-gray-600">Terjual {{ (int) ($product->terjual ?? 0) }}
                                </p>

                                <p class="text-lg font-extrabold text-blue-600 mt-2">
                                    @php
                                        $discount = $product->discounts
                                            ->filter(function ($d) {
                                                return $d->type === 'product' &&
                                                    $d->status == 1 &&
                                                    (is_null($d->expired_at) || $d->expired_at >= now());
                                            })
                                            ->first();

                                        $hargaAsli = $product->harga;
                                        $hargaDiskon = $hargaAsli;

                                        if ($discount) {
                                            if ($discount->discount_type === 'percent') {
                                                $hargaDiskon = $hargaAsli - $hargaAsli * ($discount->value / 100);
                                            } elseif ($discount->discount_type === 'nominal') {
                                                $hargaDiskon = max(0, $hargaAsli - $discount->value);
                                            }
                                        }
                                    @endphp

                                    @if ($discount)
                                        <span class="text-gray-400 line-through mr-2">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                        <span class="text-lg font-extrabold text-blue-600 mt-2">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-1 text-xs text-red-500">
                                            Diskon :
                                            ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                        </span>
                                    @else
                                        <span class="text-lg font-extrabold text-blue-600 mt-2">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </p>

                                <button data-product-id="{{ $product->id }}"
                                    class="btn-add-cart w-full mt-2 flex items-center justify-center gap-2 text-sm px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition duration-300 font-semibold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.107.272l1.638 3.276c.725 1.45.19 3.163-1.096 4.195a.999.999 0 00-.076.06l-.546.546a.997.997 0 00-.112.115l-1.096 1.096a1 1 0 101.414 1.414l.546-.546a.999.999 0 00.115-.112l1.096-1.096c1.155-.836 2.008-2.28 2.14-3.834h2.221a.997.997 0 00.112-.115l.546-.546c1.286-1.032 1.82-2.745 1.095-4.195l-1.638-3.276a.997.997 0 00-.107-.272L17.78 3H19a1 1 0 100-2h-2.586a1 1 0 00-.707.293L13.5 4.586A1 1 0 0014 6h2a1 1 0 000-2h-.22l-.462-1.848a.997.997 0 00-.107-.272L12.78 1H3zm7 11a1 1 0 11-2 0 1 1 0 012 0z">
                                        </path>
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-500">
                            <h3 class="mt-2 text-lg sm:text-xl font-bold text-gray-900">Produk Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm sm:text-base text-gray-500">Coba ubah filter atau kata kunci pencarian
                                Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>

    <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 items-end"></div>

    <div id="login-modal" class="fixed inset-0 z-40 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div
            class="relative bg-white rounded-xl shadow-2xl max-w-sm w-full p-8 z-50 transform transition-all scale-95 md:scale-100">
            <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8.707 9.293a1 1 0 00-1.414 1.414L8.586 12l-1.293 1.293a1 1 0 101.414 1.414L10 13.414l1.293 1.293a1 1 0 001.414-1.414L11.414 12l1.293-1.293a1 1 0 00-1.414-1.414L10 10.586 8.707 9.293z"
                        clip-rule="evenodd"></path>
                </svg>
                Perlu Masuk
            </h3>
            <p class="text-sm text-gray-700 mb-6">Silakan masuk untuk menggunakan fitur wishlist atau menambahkan produk ke
                keranjang. Anda tetap berada di halaman ini setelah masuk.</p>
            <div class="flex justify-end gap-3">
                <button id="modal-close-btn"
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-medium hover:bg-gray-300 transition">Tutup</button>
                <a href="/login"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Masuk</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wishlistBtns = document.querySelectorAll('.btn-wishlist');
            const addCartBtns = document.querySelectorAll('.btn-add-cart');

            // ---------- Toast helper ----------
            function ensureToastContainer() {
                let c = document.getElementById('toast-container');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'toast-container';
                    c.className = 'fixed top-6 right-6 z-50 flex flex-col gap-3 items-end';
                    document.body.appendChild(c);
                }
                return c;
            }

            function showToast(message, type = 'info', duration = 2800) {
                const container = ensureToastContainer();

                const color = {
                    info: 'bg-gray-900 text-white',
                    success: 'bg-green-600 text-white',
                    message: 'bg-red-600 text-white',
                    warning: 'bg-yellow-500 text-black'
                } [type] || 'bg-gray-900 text-white';

                const icon = {
                    info: 'ℹ️',
                    success: '✅',
                    message: '❌',
                    warning: '⚠️'
                } [type] || '';

                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-4 py-2 rounded shadow ${color}`;
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.innerHTML = `<div class="text-lg">${icon}</div><div class="text-sm">${message}</div>`;

                container.appendChild(toast);

                // animate in
                requestAnimationFrame(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                    toast.style.transition = 'all 220ms ease';
                });

                // remove after duration
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 240);
                }, duration);
            }

            // ---------- Modal helper (no redirect) ----------
            function ensureModal() {
                const modal = document.getElementById('login-modal');
                if (!modal) return null;
                return modal;
            }

            function showLoginModal() {
                const modal = ensureModal();
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            document.getElementById('modal-close-btn')?.addEventListener('click', function() {
                const modal = ensureModal();
                if (!modal) return;
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });

            // ---------- AJAX helper ----------
            async function postJSON(url, data) {
                const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                    '{{ csrf_token() }}';
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    return res;
                } catch (err) {
                    // console.message('Network message', err);
                    return {
                        ok: false,
                        status: 0,
                        json: async () => ({
                            message: 'network'
                        })
                    };
                }
            }

            window.userLoggedIn = @json(auth()->check());

            // ---------- Wishlist handlers (NO REDIRECT) ----------
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    const productSlug = this.dataset.productSlug || null;

                    // Cek login dulu
                    const isLoggedIn = !!window
                    .userLoggedIn; // window.userLoggedIn = true kalau login
                    if (!isLoggedIn) {
                        showLoginModal();
                        return;
                    }

                    // small feedback
                    showToast('Memproses wishlist…', 'info', 900);

                    try {
                        const res = await postJSON('{{ route('shop.toggleWishlist') }}', {
                            // send both to be compatible: controller can use product_slug or product_id
                            product_slug: productSlug,
                            product_id: productId
                        });

                        if (!res.ok) {
                            const errData = await res.json().catch(() => ({}));
                            showToast(errData.message || 'Gagal memproses wishlist', 'message');
                            return;
                        }

                        const data = await res.json();
                        if (data.status === 'added') {
                            showToast('Ditambahkan ke wishlist', 'success');
                            btn.classList.add('text-red-500');
                        } else if (data.status === 'removed') {
                            showToast('Dihapus dari wishlist', 'warning');
                            btn.classList.remove('text-red-500');
                        } else {
                            showToast('Respons tidak terduga', 'message');
                        }
                    } catch (err) {
                        showToast('Terjadi kesalahan. Coba lagi.', 'message');
                    }
                });
            });


            // ---------- Add to Cart handlers (NO REDIRECT) ----------
            addCartBtns.forEach(btn => {
                btn.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    const productSlug = this.dataset.productSlug || null;
                    const qty = parseInt(this.dataset.quantity || 1, 10);

                    // Cek apakah user sudah login
                    const isLoggedIn = !!window
                    .userLoggedIn; // misal window.userLoggedIn = true kalau login
                    if (!isLoggedIn) {
                        showLoginModal();
                        return;
                    }

                    showToast('Menambahkan ke keranjang…', 'info', 900);

                    try {
                        const res = await postJSON('{{ route('shop.addToCart') }}', {
                            product_id: productSlug || productId,
                            jumlah: qty,
                            source: "shop" // default harga normal
                        });

                        if (!res.ok) {
                            const errData = await res.json().catch(() => ({}));
                            showToast(errData.message || 'Gagal menambahkan ke keranjang',
                                'error');
                            return;
                        }

                        const data = await res.json();
                        if (data.status === 'ok') {
                            const total = data.jumlah || qty;
                            showToast(`Produk ditambahkan ke keranjang (total: ${total})`,
                                'success');

                            // optional: update cart count in header jika ada
                            // updateCartCount(total);
                        } else {
                            showToast('Respons tidak terduga', 'error');
                        }
                    } catch (err) {
                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                });
            });


            // close modal on Esc
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = ensureModal();
                    if (modal && !modal.classList.contains('hidden')) {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }
                }
            });
            // ---------- NEW: convert category/brand id -> slug & search -> slug-if-matches ----------
            const filterForm = document.getElementById('filter-form');
            const selectCategory = document.getElementById('filter-category');
            const selectBrand = document.getElementById('filter-brand');
            const inputSearch = document.getElementById('input-search');

            // build maps id -> slug and name -> slug
            function buildMaps(selectEl) {
                const idToSlug = {};
                const nameToSlug = {};
                if (!selectEl) return {
                    idToSlug,
                    nameToSlug
                };
                selectEl.querySelectorAll('option[data-slug]').forEach(opt => {
                    const id = opt.value;
                    const slug = opt.getAttribute('data-slug') || '';
                    const name = opt.getAttribute('data-name') || '';
                    if (id && slug) idToSlug[id] = slug;
                    if (name && slug) nameToSlug[name.toLowerCase()] = slug;
                    if (slug) nameToSlug[slug.toLowerCase()] = slug; // allow matching slug typed in search
                });
                return {
                    idToSlug,
                    nameToSlug
                };
            }

            const catMaps = buildMaps(selectCategory);
            const brandMaps = buildMaps(selectBrand);

            // on submit: build query where category & brand replaced with slug (if found).
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault(); // prevent default to build custom query
                const params = new URLSearchParams();

                // search
                const rawSearch = (inputSearch?.value || '').trim();
                if (rawSearch !== '') {
                    // if search matches a category name or slug, convert to its slug
                    const searchLower = rawSearch.toLowerCase();
                    let replaced = rawSearch;
                    if (catMaps.nameToSlug[searchLower]) replaced = catMaps.nameToSlug[searchLower];
                    else if (brandMaps.nameToSlug[searchLower]) replaced = brandMaps.nameToSlug[
                        searchLower];
                    // else keep rawSearch
                    params.set('search', replaced);
                }

                // category: prefer slug
                const catVal = selectCategory?.value || '';
                if (catVal !== '') {
                    const slug = catMaps.idToSlug[catVal] || catVal;
                    params.set('category', slug);
                }

                // brand: prefer slug
                const brandVal = selectBrand?.value || '';
                if (brandVal !== '') {
                    const slug = brandMaps.idToSlug[brandVal] || brandVal;
                    params.set('brand', slug);
                }

                // price
                const priceMin = document.querySelector('input[name="price_min"]').value || '';
                const priceMax = document.querySelector('input[name="price_max"]').value || '';
                if (priceMin !== '') params.set('price_min', priceMin);
                if (priceMax !== '') params.set('price_max', priceMax);

                // keep sort if present
                const currentSort = new URLSearchParams(window.location.search).get('sort');
                if (currentSort) params.set('sort', currentSort);

                // navigate to shop with slugged query (GET)
                const url = '{{ route('shop.index') }}' + (params.toString() ? ('?' + params.toString()) :
                    '');
                window.location.href = url;
            });
            // --------------------------------------------------------------------------------------
        });
    </script>

@endsection
