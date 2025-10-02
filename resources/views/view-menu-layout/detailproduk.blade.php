@extends('Layouts.main')

@section('judul', $product->nama_produk . ' | AGA IT COMPUTER')

@section('content')
    <section class="max-w-7xl mx-auto py-8 md:py-12 px-4 bg-white rounded-xl shadow-lg">
        <!-- Breadcrumb -->
        <nav class="w-full text-xs sm:text-sm font-medium text-gray-500 mb-6">
            <a href="/" class="hover:text-blue-600 transition">Home</a>
            <span class="mx-1 sm:mx-2">/</span>
            <a href="{{ url('/shop') }}" class="hover:text-blue-600 transition">Shop</a>
            <span class="mx-1 sm:mx-2">/</span>
            <span class="text-gray-800 font-semibold break-words">{{ $product->nama_produk }}</span>
        </nav>

        <!-- GRID UTAMA: Gambar & Info Produk -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 md:gap-12">
            <!-- Kolom Gambar -->
            <div class="space-y-4 lg:col-span-1 xl:col-span-2">
                <div class="relative w-full bg-gray-100 rounded-xl overflow-hidden shadow-inner cursor-pointer"
                    id="main-image-container">
                    @php
                        $mainImage = optional($product->images->first())->path ?? ($product->foto ?? null);
                    @endphp
                    <img id="main-product-image"
                        src="{{ $mainImage ? asset(Storage::url($mainImage)) : asset('image/default-product.png') }}"
                        alt="{{ $product->nama_produk }}"
                        class="w-full max-h-[420px] sm:max-h-[500px] object-contain p-4 transition-transform duration-300 hover:scale-105">
                </div>

                <!-- Thumbnail Gambar -->
                <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                    @php
                        $allImages = collect();
                        if ($product->foto) {
                            $allImages->push(['image_path' => $product->foto]);
                        }
                        if ($product->images) {
                            $allImages = $allImages->merge(
                                $product->images->map(fn($img) => ['image_path' => $img->image_path]),
                            );
                        }
                    @endphp

                    @forelse ($allImages as $img)
                        <button type="button"
                            class="thumb shrink-0 w-20 sm:w-24 h-20 sm:h-24 border-2 border-transparent rounded-lg overflow-hidden transition hover:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            data-src="{{ asset(Storage::url($img['image_path'])) }}">
                            <img src="{{ asset(Storage::url($img['image_path'])) }}" class="w-full h-full object-cover">
                        </button>
                    @empty
                        <div class="w-20 h-20 border rounded-lg overflow-hidden">
                            <img src="{{ asset('image/default-product.png') }}" class="w-full h-full object-cover">
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Kolom Informasi Produk -->
            <div class="space-y-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">{{ $product->nama_produk }}</h1>

                <!-- Rating dan Penjualan -->
                <div class="flex flex-wrap items-center gap-4 text-sm md:text-base">
                    <div class="flex items-center gap-1 text-yellow-500 font-bold">
                        ★ {{ number_format($avgRating, 1) }}
                    </div>
                    <span class="text-gray-500">({{ $totalReviews }} ulasan)</span>
                    <span class="text-gray-500">Terjual {{ (int) ($product->terjual ?? 0) }}</span>
                </div>

                <!-- Harga -->
                <div class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-green-700">
                    @php
                        $discount = $product->discounts
                            ->filter(
                                fn($d) => $d->type === 'product' &&
                                    $d->status == 1 &&
                                    (is_null($d->expired_at) || $d->expired_at >= now()),
                            )
                            ->first();
                        $hargaAsli = $product->harga;
                        $hargaDiskon = $hargaAsli;

                        if ($discount) {
                            $hargaDiskon =
                                $discount->discount_type === 'percent'
                                    ? $hargaAsli - $hargaAsli * ($discount->value / 100)
                                    : max(0, $hargaAsli - $discount->value);
                        }
                    @endphp

                    @if ($discount)
                        <span class="text-gray-400 line-through mr-2 text-lg">Rp
                            {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        <span class="ml-1 text-xs text-red-500 block sm:inline">
                            (Diskon
                            {{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                        </span>
                    @else
                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    @endif
                </div>

                <!-- Info Tambahan -->
                <div class="text-sm md:text-base text-gray-600 space-y-2">
                    <div><span class="font-semibold">Kategori:</span> <a
                            href="{{ url('shop') . '?category=' . urlencode($product->category->slug) }}"
                            class="text-blue-600 hover:underline">{{ $product->category->nama_kategori }}</a></div>
                    <div><span class="font-semibold">Brand:</span> <span
                            class="font-medium text-gray-800">{{ $product->brand->name }}</span></div>
                    <div><span class="font-semibold">Berat:</span> <span
                            class="font-medium text-gray-800">{{ $product->berat }}g</span></div>
                </div>

                <!-- Stok & Aksi -->
                <div class="space-y-4 p-4 sm:p-6 bg-gray-50 rounded-xl shadow-inner border border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-600">Stok Tersedia:</span>
                        <span class="font-bold text-gray-800">{{ $product->stok ?? 0 }}</span>
                    </div>

                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input id="qty" type="number" value="1" min="1"
                            max="{{ max(1, (int) $product->stok) }}"
                            class="w-full border-gray-300 rounded-lg p-3 text-lg text-center focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-between pt-2">
                        <span class="text-sm font-medium text-gray-600">Total Harga</span>
                        <span id="totalPrice" class="text-xl font-extrabold text-green-700">Rp
                            {{ number_format($product->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="space-y-3 mt-4">
                        <button id="btn-add-cart" data-product-id="{{ $product->id }}"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2">
                            🛒 Tambah ke Keranjang
                        </button>

                        @auth
                            <button id="btn-buy-now" data-id="{{ $product->id }}"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2">
                                ⚡ Beli Langsung
                            </button>
                        @else
                            <p class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-bold text-center cursor-pointer hover:bg-gray-300"
                                onclick="window.location.href='{{ route('Masuk') }}'">Masuk untuk Beli</p>
                        @endauth

                        <button id="btn-wishlist" data-product-id="{{ $product->id }}"
                            class="w-full border border-gray-300 rounded-lg py-3 text-gray-800 font-medium hover:bg-gray-100 flex items-center justify-center gap-2">
                            ❤️ Tambah ke Wishlist
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div id="image-modal" class="fixed inset-0 z-[99] hidden flex items-center justify-center bg-black bg-opacity-90">
            <div class="relative w-11/12 md:w-3/4 max-w-4xl max-h-[90%] p-4">
                <button id="close-modal"
                    class="absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <img id="modal-image" src="" alt="Product Image"
                    class="max-w-full max-h-[80vh] mx-auto object-contain">

                <div id="modal-thumbnails" class="flex justify-center gap-3 mt-4 overflow-x-auto no-scrollbar">
                    @if ($allImages->count() > 0)
                        @foreach ($allImages as $img)
                            <button type="button"
                                class="modal-thumb shrink-0 w-20 h-20 border-2 border-transparent rounded-md overflow-hidden transition-all duration-300 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                data-src="{{ asset(Storage::url($img['image_path'])) }}">
                                <img src="{{ asset(Storage::url($img['image_path'])) }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- TAB: Deskripsi / Spesifikasi / Ulasan -->
    <section class="max-w-7xl mx-auto mt-8 md:mt-12 px-4 py-8 bg-white rounded-xl shadow-lg">
        <div class="flex gap-4 sm:gap-6 font-semibold border-b border-gray-200 pb-3 mb-6 overflow-x-auto no-scrollbar text-sm sm:text-base"
            id="tab-container">
            <button class="tab-btn px-4 py-2 text-blue-600 border-b-2 border-blue-600 whitespace-nowrap"
                data-tab="deskripsi">Deskripsi</button>
            <button
                class="tab-btn px-4 py-2 text-gray-500 hover:text-blue-600 border-b-2 border-transparent whitespace-nowrap"
                data-tab="spesifikasi">Spesifikasi</button>
            <button
                class="tab-btn px-4 py-2 text-gray-500 hover:text-blue-600 border-b-2 border-transparent whitespace-nowrap"
                data-tab="ulasan">Ulasan ({{ $totalReviews }})</button>
        </div>

        <!-- Deskripsi -->
        <div id="deskripsi" class="tab-panel text-gray-700">
            <h3 class="font-bold text-xl mb-3">Deskripsi Produk</h3>
            <div class="prose max-w-none text-base leading-relaxed break-words">
                {!! nl2br(e($product->deskripsi ?? ($product->deskirpsi ?? '-'))) !!}
            </div>
        </div>

        <!-- Spesifikasi -->
        <div id="spesifikasi" class="tab-panel hidden text-gray-700">
            <h3 class="font-bold text-xl mb-3">Spesifikasi Detail</h3>
            @if ($product->specifications && $product->specifications->count())
                <ul class="space-y-2">
                    @foreach ($product->specifications as $spec)
                        <li class="flex flex-wrap gap-2"><span class="font-semibold">{{ $spec->key }}:</span>
                            <span>{{ $spec->value }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-500">Spesifikasi belum tersedia.</div>
            @endif
        </div>

        <!-- Ulasan -->
        <div id="ulasan" class="tab-panel hidden">
            <div class="flex flex-wrap items-center gap-4 mb-6 pb-4 border-b border-gray-200">
                <div class="text-4xl sm:text-5xl font-extrabold text-yellow-500">★ {{ number_format($avgRating, 1) }}
                </div>
                <div class="text-gray-600 text-sm sm:text-base">
                    <div class="font-semibold">{{ $totalReviews }} ulasan</div>
                    <div>Berdasarkan ulasan pembeli</div>
                </div>
            </div>

            <!-- Form Ulasan -->
            <div class="mb-8 p-4 sm:p-6 bg-gray-50 rounded-xl shadow-sm border border-gray-200">
                <h4 class="font-bold text-lg mb-4">Berikan Ulasanmu</h4>
                @auth
                    <form id="review-form" class="space-y-4">
                        <div>
                            <label for="review-rating" class="text-sm font-medium">Rating</label>
                            <select id="review-rating" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="5">5 — Sangat Bagus</option>
                                <option value="4">4 — Bagus</option>
                                <option value="3">3 — Cukup</option>
                                <option value="2">2 — Kurang</option>
                                <option value="1">1 — Buruk</option>
                            </select>
                        </div>
                        <div>
                            <label for="review-komentar" class="text-sm font-medium">Ulasan</label>
                            <textarea id="review-komentar" rows="4"
                                class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Tulis pengalamanmu..."></textarea>
                        </div>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-md">Kirim
                            Ulasan</button>
                    </form>
                @else
                    <div class="text-sm text-gray-600"><a href="#" id="btn-open-login-for-review"
                            class="text-blue-600 font-medium hover:underline">Masuk</a> untuk memberikan ulasan.</div>
                @endauth
            </div>

            <!-- List Ulasan -->
            <div id="reviews-list" class="space-y-6">
                @forelse($reviews as $r)
                    <div class="bg-gray-50 rounded-lg p-5 shadow-sm border">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($r->user->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <div class="font-semibold">{{ $r->user->name ?? 'User' }}</div>
                                    <div class="text-xs text-gray-500">{{ $r->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="text-yellow-500 font-semibold">★ {{ $r->rating }}</div>
                        </div>
                        <div class="mt-4 text-sm text-gray-700">{{ $r->komentar }}</div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada ulasan.</p>
                        <p class="text-sm text-gray-400">Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $reviews->links() }}</div>
        </div>
    </section>

    <style>
        /* Custom scrollbar untuk tampilan yang lebih bersih */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    {{-- Rekomendasi Produk & Produk Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-end mb-6 border-b-2 border-green-500 pb-2">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Rekomendasi Untukmu 💖</h2>
            <a href="/shop"
                class="text-green-600 font-semibold text-xs sm:text-sm hover:underline hover:text-green-700 transition">Lihat
                Semua →</a>
        </div>

        <div class="relative group">
            <div id="rekomendasiSlider"
                class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 sm:gap-6 pb-2 no-scrollbar px-1">
                @foreach ($rekomendasiProduk as $produk)
                    <div
                        class="min-w-[220px] sm:min-w-[260px] md:min-w-[300px] lg:min-w-[340px] xl:min-w-[380px] flex-shrink-0 snap-start bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}" class="block">
                            <img src="{{ $produk->foto ? asset(Storage::url($produk->foto)) : '/image/default-product.png' }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-full h-32 sm:h-40 object-cover rounded-t-xl transition-transform duration-500 hover:scale-110">
                        </a>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-sm sm:text-base font-bold line-clamp-2 text-gray-800 mb-1">
                                {{ $produk->nama_produk }}</h3>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-yellow-400 text-xs sm:text-sm">★
                                    {{ number_format($produk->rating ?? 0, 1) }}</div>
                                <div class="text-gray-500 text-[10px] sm:text-xs">({{ $produk->ulasan_count ?? 0 }})</div>
                            </div>
                            <p class="text-[10px] sm:text-xs text-gray-600">Terjual {{ (int) ($produk->terjual ?? 0) }}
                            </p>
                            <div class="mt-2 sm:mt-3 flex items-center justify-between gap-2">
                                <div class="text-base sm:text-xl font-extrabold text-green-700">
                                    @php
                                        $discount = $produk->discounts
                                            ->filter(function ($d) {
                                                return $d->type === 'product' &&
                                                    $d->status == 1 &&
                                                    (is_null($d->expired_at) || $d->expired_at >= now());
                                            })
                                            ->first();

                                        $hargaAsli = $produk->harga;
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
                                        <span class="text-xl font-extrabold text-green-700">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-1 text-xs text-red-500">
                                            Diskon :
                                            ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                        </span>
                                    @else
                                        <span class="text-xl font-extrabold text-green-700">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}"
                                    class="text-white bg-green-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-green-700 transition-colors">Lihat</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- toast/modal (reuse) -->
    <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 items-end"></div>
    <div id="login-modal" class="fixed inset-0 z-40 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative bg-white rounded-lg shadow-lg max-w-sm w-full p-6 z-50">
            <h3 class="text-lg font-semibold mb-2">Perlu Masuk</h3>
            <p class="text-sm text-gray-700 mb-4">Silakan masuk untuk menggunakan wishlist, menambahkan ke keranjang, atau
                memberi ulasan.</p>
            <div class="flex justify-end gap-2">
                <a href="{{ route('Masuk') }}" class="px-3 py-1 rounded bg-green-600 text-white">Masuk</a>
                <button id="modal-close-btn" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thumbnail -> change main image
            document.querySelectorAll('.thumb').forEach(btn => {
                btn.addEventListener('click', () => {
                    const src = btn.dataset.src;
                    if (src) document.getElementById('main-product-image').src = src;
                });
            });

            // Tabs
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.addEventListener('click', () => {
                    document.querySelectorAll('.tab-btn').forEach(x => x.classList.remove(
                        'text-blue-600'));
                    b.classList.add('text-blue-600');
                    const tab = b.dataset.tab;
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
                    document.getElementById(tab).classList.remove('hidden');
                });
            });

            // Total price update
            const qtyInput = document.getElementById('qty');
            const totalPriceEl = document.getElementById('totalPrice');
            const price = {{ (int) $product->harga }};

            function updateTotal() {
                const q = Math.max(1, parseInt(qtyInput.value || 1, 10));
                const total = q * price;
                totalPriceEl.textContent = 'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            qtyInput.addEventListener('input', updateTotal);

            // toast/modal helpers
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
                    error: 'bg-red-600 text-white',
                    warning: 'bg-yellow-500 text-black'
                } [type] || 'bg-gray-900 text-white';
                const icon = {
                    info: 'ℹ️',
                    success: '✅',
                    error: '❌',
                    warning: '⚠️'
                } [type] || '';
                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-4 py-2 rounded shadow ${color}`;
                toast.innerHTML = `<div class="text-lg">${icon}</div><div class="text-sm">${message}</div>`;
                container.appendChild(toast);
                setTimeout(() => toast.remove(), duration);
            }

            function ensureModal() {
                return document.getElementById('login-modal');
            }

            function showLoginModal() {
                const m = ensureModal();
                if (m) {
                    m.classList.remove('hidden');
                    m.classList.add('flex');
                }
            }
            document.getElementById('modal-close-btn')?.addEventListener('click', () => {
                const m = ensureModal();
                if (m) {
                    m.classList.remove('flex');
                    m.classList.add('hidden');
                }
            });

            // AJAX helper
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
                } catch (e) {
                    return {
                        ok: false,
                        status: 0,
                        json: async () => ({
                            error: 'network'
                        })
                    };
                }
            }

            // Review submit (AJAX)
            const reviewForm = document.getElementById('review-form');
            if (reviewForm) {
                reviewForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const rating = parseInt(document.getElementById('review-rating').value || 5, 10);
                    const komentar = document.getElementById('review-komentar').value.trim();
                    showToast('Mengirim ulasan...', 'info', 900);

                    try {
                        const res = await postJSON('{{ route('produk.review', $product->slug) }}', {
                            rating,
                            komentar
                        });
                        if (!res.ok) {
                            if (res.status === 401) {
                                showLoginModal();
                                return;
                            }
                            const err = await res.json().catch(() => ({}));
                            const msg = (err.messages) ? Object.values(err.messages).flat().join(' ') :
                                (err.error || 'Gagal mengirim ulasan');
                            showToast(msg, 'error');
                            return;
                        }

                        const data = await res.json();
                        if (data.status === 'ok') {
                            showToast('Ulasan terkirim', 'success');

                            // prepend new review to reviews list
                            const r = data.review;
                            const list = document.getElementById('reviews-list');
                            const wrapper = document.createElement('div');
                            wrapper.className = 'border rounded p-3';
                            wrapper.innerHTML = `
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center text-sm font-semibold">
                  ${ (r.user_name||'U').charAt(0).toUpperCase() }
                </div>
                <div>
                  <div class="font-semibold text-sm">${ r.user_name || 'User' }</div>
                  <div class="text-xs text-gray-500">${ r.created_at }</div>
                </div>
              </div>
              <div class="text-sm text-yellow-400 font-semibold">★ ${ r.rating }</div>
            </div>
            <div class="mt-2 text-sm text-gray-700">${ r.komentar }</div>
          `;
                            list.prepend(wrapper);

                            // update avg & total display
                            document.querySelectorAll(
                                '.text-3xl, .text-lg.font-bold.text-green-600, .tab-btn[data-tab="ulasan"]'
                            ).forEach(el => {
                                // update review counters where applicable
                            });
                            // update avg and total in page header elements
                            document.querySelectorAll('.text-yellow-400.text-lg.font-semibold').forEach(
                                el => el.textContent = '★ ' + (data.avgRating ||
                                    {{ $avgRating }}));
                            document.querySelectorAll('.tab-btn[data-tab="ulasan"]').forEach(el => el
                                .textContent = 'Ulasan (' + (data.totalReviews ||
                                    {{ $totalReviews }}) + ')');
                            // clear form
                            document.getElementById('review-komentar').value = '';
                        } else {
                            showToast('Respons tidak terduga', 'error');
                        }
                    } catch (err) {
                        // console.error(err);
                        showToast('Terjadi kesalahan', 'error');
                    }
                });
            } else {
                // if not logged in, open login modal when clicking the prompt
                document.getElementById('btn-open-login-for-review')?.addEventListener('click', showLoginModal);
            }

            window.userLoggedIn = @json(auth()->check());

            // add to cart dari detail produk (SHOP)
            document.getElementById('btn-add-cart')?.addEventListener('click', async function() {
                const qty = Math.max(1, parseInt(qtyInput.value || 1, 10));
                const productSlug = this.dataset.productSlug || null;
                const productId = this.dataset.productId || null;

                // cek login
                const isLoggedIn = !!window.userLoggedIn;
                if (!isLoggedIn) {
                    showLoginModal();
                    return;
                }

                showToast('Menambahkan ke keranjang...', 'info', 900);

                try {
                    const res = await postJSON('{{ route('shop.addToCart') }}', {
                        product_id: productSlug || productId,
                        jumlah: qty,
                        source: "shop"
                    });

                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        showToast(err.error || 'Gagal menambahkan ke keranjang', 'error');
                        return;
                    }

                    const data = await res.json();
                    if (data.status === 'ok') {
                        showToast(
                            `Ditambahkan ke keranjang (total: ${data.jumlah || qty})`,
                            'success'
                        );
                    }
                } catch (e) {
                    showToast('Terjadi kesalahan', 'error');
                }
            });


            document.getElementById('btn-buy-now')?.addEventListener('click', async function() {
                const btn = this;
                const qty = Math.max(1, parseInt(qtyInput.value || 1, 10));
                const addCartEl = document.getElementById('btn-add-cart');
                const productId = addCartEl?.dataset.productId;

                if (!productId) {
                    showToast('❌ Produk tidak ditemukan (productId null)', 'error');
                    return;
                }

                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');

                try {
                    const res = await fetch("{{ route('product.buyNow') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            jumlah: qty
                        })
                    });

                    let data;
                    const contentType = res.headers.get("content-type");

                    if (contentType && contentType.includes("application/json")) {
                        data = await res.json();
                    } else {
                        const raw = await res.text();
                        // console.error("❌ RAW ERROR RESPONSE:", raw);
                        data = {
                            message: "Server balikin HTML, cek console.",
                            raw
                        };
                    }

                    if (!res.ok) {
                        let errorMsg = data.message || `HTTP ${res.status} ${res.statusText}`;
                        if (data.file && data.line) {
                            errorMsg += ` [${data.file}:${data.line}]`;
                        }
                        showToast(errorMsg, "error", 6000);
                        // console.error("❌ Buy Now Error:", data);
                        return;
                    }

                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        showToast(data.message || "Terjadi kesalahan.", "error", 4000);
                    }
                } catch (err) {
                    // console.error("🔥 Exception Buy Now:", err);
                    showToast("🔥 Exception: " + err.message, "error", 6000);

                } finally {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            });




            // wishlist single button
            document.getElementById('btn-wishlist')?.addEventListener('click', async function() {
                const productSlug = this.dataset.productSlug || null;
                const productId = this.dataset.productId || null;

                // cek login
                const isLoggedIn = !!window.userLoggedIn;
                if (!isLoggedIn) {
                    showLoginModal();
                    return;
                }

                showToast('Memproses wishlist...', 'info', 900);

                try {
                    const res = await postJSON('{{ route('shop.toggleWishlist') }}', {
                        product_id: productSlug || productId
                    });

                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        showToast(err.error || 'Gagal', 'error');
                        return;
                    }

                    const data = await res.json();
                    if (data.status === 'added') {
                        showToast('Ditambahkan ke wishlist', 'success');
                    } else if (data.status === 'removed') {
                        showToast('Dihapus dari wishlist', 'warning');
                    }
                } catch (e) {
                    showToast('Terjadi kesalahan', 'error');
                }
            });

        }); // DOMContentLoaded
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('main-product-image');
            const thumbs = document.querySelectorAll('.thumb');
            const imageModal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');
            const closeModalBtn = document.getElementById('close-modal');

            // Fungsi untuk mengupdate border thumbnail aktif
            function updateActiveThumb(currentSrc) {
                thumbs.forEach(t => {
                    t.classList.remove('border-blue-500', 'ring-2');
                    if (t.getAttribute('data-src') === currentSrc) {
                        t.classList.add('border-blue-500', 'ring-2');
                    }
                });
                // Update juga thumbnail di modal
                const modalThumbs = document.querySelectorAll('.modal-thumb');
                modalThumbs.forEach(t => {
                    t.classList.remove('border-blue-500', 'ring-2');
                    if (t.getAttribute('data-src') === currentSrc) {
                        t.classList.add('border-blue-500', 'ring-2');
                    }
                });
            }

            // Set border awal pada thumbnail utama
            updateActiveThumb(mainImage.src);

            // Ganti gambar utama saat thumbnail diklik
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const newSrc = this.getAttribute('data-src');
                    mainImage.src = newSrc;
                    updateActiveThumb(newSrc);
                });
            });

            // Tampilkan modal saat gambar utama diklik
            document.getElementById('main-image-container').addEventListener('click', function() {
                const currentSrc = mainImage.src;
                modalImage.src = currentSrc;
                imageModal.classList.remove('hidden');
                updateActiveThumb(currentSrc);
            });

            // Sembunyikan modal saat tombol close diklik
            closeModalBtn.addEventListener('click', function() {
                imageModal.classList.add('hidden');
            });

            // Sembunyikan modal saat area luar gambar diklik
            imageModal.addEventListener('click', function(e) {
                if (e.target.id === 'image-modal') {
                    imageModal.classList.add('hidden');
                }
            });

            // Ganti gambar di modal saat thumbnail modal diklik
            const modalThumbs = document.querySelectorAll('.modal-thumb');
            modalThumbs.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const newSrc = this.getAttribute('data-src');
                    modalImage.src = newSrc;
                    updateActiveThumb(newSrc);
                });
            });
        });
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #main-product-image.hover\:scale-105:hover {
            transform: scale(1.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabContainer = document.getElementById('tab-container');
            const tabButtons = tabContainer.querySelectorAll('.tab-btn');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Hapus kelas aktif dari semua tombol
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-blue-600', 'border-blue-600');
                        btn.classList.add('text-gray-500', 'hover:text-blue-600',
                            'border-transparent');
                    });

                    // Tambahkan kelas aktif pada tombol yang diklik
                    this.classList.add('text-blue-600', 'border-blue-600');
                    this.classList.remove('text-gray-500', 'hover:text-blue-600',
                        'border-transparent');

                    // Anda dapat menambahkan logika untuk menampilkan konten tab di sini
                    const targetTab = this.getAttribute('data-tab');
                    // console.log(`Menampilkan konten untuk tab: ${targetTab}`);
                    // Contoh: document.getElementById(targetTab).classList.remove('hidden');
                });
            });

            // Setel tab 'deskripsi' sebagai aktif secara default
            const defaultTab = tabContainer.querySelector('[data-tab="deskripsi"]');
            if (defaultTab) {
                defaultTab.classList.add('text-blue-600', 'border-blue-600');
                defaultTab.classList.remove('text-gray-500', 'hover:text-blue-600', 'border-transparent');
            }
        });
    </script>

@endsection
