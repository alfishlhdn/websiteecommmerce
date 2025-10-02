@extends('Layouts.main')

@section('judul', 'AGA IT COMPUTER | Toko Komputer & Service Laptop Malang')

@section('content')

    <section class="w-full py-6 md:py-12 px-4">
        <div class="max-w-7xl mx-auto relative overflow-hidden rounded-2xl md:rounded-3xl shadow-2xl" x-data="{ idx: 0, interval: null, init() { this.startAutoplay(); }, startAutoplay() { this.interval = setInterval(() => { this.idx = (this.idx + 1) % this.$el.querySelectorAll('#slider > a').length; }, 5000); }, stopAutoplay() { clearInterval(this.interval); } }"
            @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
            <div id="slider" class="flex transition-transform duration-700 ease-in-out"
                :style="'transform: translateX(-' + (idx * 100) + '%)'">
                @php $banners = \App\Models\Banner::where('is_active', 1)->get(); @endphp
                @foreach ($banners as $banner)
                    <a href="" class="flex-shrink-0 w-full">
                        <img src="{{ asset(Storage::url($banner->image)) }}" alt="{{ $banner->title }}"
                            class="w-full h-40 sm:h-60 md:h-[400px] lg:h-[600px] object-cover">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 border-b-2 border-green-500 inline-block">
            Kategori ✨
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 text-center">
            @foreach ($kategori->take(10) as $item)
                {{-- tampilkan max 10 kategori --}}
                <a href="{{ url('shop') . '?category=' . urlencode($item->slug) }}"
                    class="flex flex-col items-center justify-center p-2 rounded-xl bg-white shadow-sm hover:shadow-lg hover:text-green-600 transition-all duration-300 transform hover:-translate-y-1">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20 mb-2 rounded-full flex items-center justify-center overflow-hidden border border-gray-200">
                        <img src="{{ $item->icon ? asset('storage/' . $item->icon) : '/image/default-category.png' }}"
                            alt="{{ $item->nama_kategori }}" class="w-full h-full object-cover p-1">
                    </div>
                    <span class="text-xs sm:text-sm font-semibold mt-1">{{ $item->nama_kategori }}</span>
                </a>
            @endforeach
        </div>

        {{-- Tampilkan kategori terbatas --}}
        @foreach ($kategori->take($limitKategori) as $k)
            <div class="kategori-item">
                {{ $k->nama }}
            </div>
        @endforeach

        {{-- Tombol Lihat Semua kalau kategori lebih dari limit --}}
        @if ($kategori->count() > $limitKategori)
            <div class="mt-6 text-center">
                <a href="{{ url('shop') }}"
                    class="inline-block px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow hover:bg-green-700 transition-all">
                    Lihat Semua →
                </a>
            </div>
        @endif

    </section>



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
                            <p class="text-[10px] sm:text-xs text-gray-600">Terjual {{ $produk->terjual ?? 0 }}</p>
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


    <section class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-end mb-6 border-b-2 border-green-500 pb-2">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Produk Terbaru 🚀</h2>
            <a href="/shop"
                class="text-green-600 font-semibold text-xs sm:text-sm hover:underline hover:text-green-700 transition">
                Lihat Semua →
            </a>
        </div>

        <div class="relative group">
            <div id="produkterbaru"
                class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 sm:gap-6 pb-2 no-scrollbar px-1">
                @foreach ($produkTerbaru as $produk)
                    <div
                        class="min-w-[220px] sm:min-w-[260px] md:min-w-[300px] lg:min-w-[340px] xl:min-w-[380px] flex-shrink-0 snap-start bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}" class="block">
                            <img src="{{ $produk->foto ? asset(Storage::url($produk->foto)) : '/image/default-product.png' }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-full h-32 sm:h-40 object-cover rounded-t-xl transition-transform duration-500 hover:scale-110">
                        </a>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-sm sm:text-base font-bold line-clamp-2 text-gray-800 mb-1">
                                {{ $produk->nama_produk }}
                            </h3>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-yellow-400 text-xs sm:text-sm">★
                                    {{ number_format($produk->rating ?? 0, 1) }}</div>
                                <div class="text-gray-500 text-[10px] sm:text-xs">({{ $produk->ulasan_count ?? 0 }})</div>
                            </div>
                            <p class="text-[10px] sm:text-xs text-gray-600">Terjual {{ (int) ($produk->terjual ?? 0) }}</p>
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
                                        <span class="text-gray-400 line-through mr-2 text-xs sm:text-sm">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                        <span>
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-1 text-[10px] sm:text-xs text-red-500">
                                            Diskon :
                                            ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                        </span>
                                    @else
                                        <span>Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}"
                                    class="text-white bg-green-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-green-700 transition-colors">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol geser kiri/kanan --}}
            <button onclick="scrollById('-','produkterbaru')" aria-label="Geser kiri"
                class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-white/80 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button onclick="scrollById('+','produkterbaru')" aria-label="Geser kanan"
                class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-white/80 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>

    <script>
        // ===== Slider (banner) =====
        (function() {
            const slider = document.getElementById('slider');
            const slides = slider.children.length;
            let idx = 0;
            let autoplay = true;
            let autoplayInterval = 5000; // 5s

            // Build dots
            const dotsWrap = document.getElementById('sliderDots');
            for (let i = 0; i < slides; i++) {
                const btn = document.createElement('button');
                btn.className = 'dot w-3 h-3 rounded-full transition-all';
                btn.setAttribute('aria-label', 'Slide ' + (i + 1));
                btn.dataset.index = i;
                btn.addEventListener('click', () => goToSlide(i));
                // dotsWrap.appendChild(btn);
            }

            // const updateDots = () => {
            //     const dots = dotsWrap.children;
            //     for (let i = 0; i < dots.length; i++) {
            //         dots[i].classList.toggle('bg-gray-800', i === idx);
            //         dots[i].classList.toggle('bg-gray-400', i !== idx);
            //     }
            // };

            // function setTranslate() {
            //     slider.style.transform = `translateX(-${idx * 100}%)`;
            //     updateDots();
            // }

            window.nextSlide = function() {
                idx = (idx + 1) % slides;
                // setTranslate();
            };

            // window.prevSlide = function() {
            //     idx = (idx - 1 + slides) % slides;
            //     setTranslate();
            // };

            // window.goToSlide = function(i) {
            //     idx = i;
            //     setTranslate();
            // };

            // Autoplay
            let timer = setInterval(() => {
                if (autoplay) nextSlide();
            }, autoplayInterval);

            // Pause autoplay on hover/focus
            const parent = slider.parentElement;
            parent.addEventListener('mouseenter', () => autoplay = false);
            parent.addEventListener('mouseleave', () => autoplay = true);

        })();

        // ===== Horizontal carousels: scroll helpers =====
        function scrollById(dir = '+', id) {
            const el = document.getElementById(id);
            if (!el) return;
            const card = el.querySelector('div');
            const cardWidth = (card ? card.getBoundingClientRect().width : 240) + 16; // gap
            if (dir === '+') {
                el.scrollBy({
                    left: cardWidth * 2,
                    behavior: 'smooth'
                });
            } else {
                el.scrollBy({
                    left: -cardWidth * 2,
                    behavior: 'smooth'
                });
            }
        }

        // Support arrow keys for focused carousels (optional)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                const active = document.activeElement;
                if (active && active.closest && (active.closest('#rekomendasiSlider') || active.closest(
                        '#produkterbaru'))) {
                    e.preventDefault();
                    if (e.key === 'ArrowLeft') scrollById('-', active.closest('.flex').id);
                    if (e.key === 'ArrowRight') scrollById('+', active.closest('.flex').id);
                }
            }
        });
    </script>


@endsection
