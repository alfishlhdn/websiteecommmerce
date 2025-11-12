@php
    $store = App\Models\Store_Setiing::first();
@endphp

<!-- 🔹 Navbar Top Info -->
<div class="bg-white text-xs border-b">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-2 gap-2">
        <!-- kiri -->
        <div class="flex flex-wrap justify-center sm:justify-start gap-3">
            <a href="/tentang-kami" class="hover:underline">Tentang Kami</a>
            <a href="/cara-belanja" class="hover:underline">Cara Belanja</a>
            @auth
                @if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin' || auth()->user()->role == 'client')
                    <a href="/price-list" class="hover:underline">Price List</a>
                @endif
            @endauth


        </div>

        <!-- kanan -->
        <div class="flex flex-wrap justify-center sm:justify-end gap-2 text-center sm:text-right">
            <span>Email: <a href="mailto:{{ $store->email ?? 'AGA COMPUTER'}}" class="font-semibold">{{ $store->email ?? 'AGA COMPUTER'}}</a></span>
            <span class="hidden sm:inline">|</span>
            <span>Phone: <a href="tel:{{ $store->phone ?? 'AGA COMPUTER'}}" class="font-semibold">{{ $store->phone ?? 'AGA COMPUTER'}}</a></span>
        </div>
    </div>
</div>

<!-- 🔹 Navbar Utama -->
<header class="bg-gradient-to-r from-blue-900 to-blue-600 text-white shadow sticky top-0 z-40">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-4 py-4 gap-3">

        <!-- Logo + Nama Toko -->
        <div class="flex items-center justify-between w-full md:w-auto">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset(Storage::url($store->logo)) ?? 'AGA COMPUTER' }}" alt="Logo AGA IT COMPUTER"
                    class="w-10 h-10 rounded-full">
                <!-- ⬇️ Desktop & Tablet -->
                <div class="hidden sm:block">
                    <div class="text-lg font-semibold leading-tight">
                        {{ $store->store_name ?? 'Nama Toko' }}
                    </div>
                    <div class="text-xs text-blue-200">Toko Komputer & Service Laptop Malang</div>
                </div>
                <!-- ⬇️ Mobile -->
                <div class="sm:hidden flex flex-col">
                    <div class="text-base font-semibold leading-tight">
                        {{ $store->store_name ?? 'Nama Toko' }}
                    </div>
                    <div class="text-[10px] text-blue-200">Toko Komputer & Service Laptop</div>
                </div>
            </a>

            <!-- ⬇️ Toggle + Wishlist + Cart (khusus mobile) -->
            <div class="flex items-center gap-4 sm:hidden">
                <!-- 🔹 Wishlist (Mobile) -->
                <button onclick="togglePopup('wishlistMobilePopup')" class="focus:outline-none sm:hidden">
                    <span class="relative">
                        <i class="far fa-heart text-xl cursor-pointer"></i>
                        @php $wc = $wishlistCount ?? 0; @endphp
                        <span id="wishlist-count"
                            class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] px-1 rounded-full {{ $wc == 0 ? 'opacity-60 scale-90' : '' }}">
                            {{ $wc }}
                        </span>
                    </span>
                </button>

                <!-- 🔹 Wishlist Popup (Mobile Version - Centered) -->
                <div id="wishlistMobilePopup"
                    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden sm:hidden flex items-center justify-center"
                    onclick="closePopupOutside(event, 'wishlistMobilePopup')">
                    <div class="bg-white w-full max-w-xs rounded-lg shadow-lg p-4 max-h-[80vh] overflow-y-auto relative"
                        onclick="event.stopPropagation()">

                        <!-- Header -->
                        <div class="flex justify-between items-center border-b pb-2 mb-3">
                            <h3 class="text-sm text-black font-semibold">Produk Favoritmu</h3>

                            <!-- Tombol Tutup -->
                            <button onclick="togglePopup('wishlistMobilePopup')"
                                class="text-gray-500 hover:text-red-500 text-lg font-bold">
                                ×
                            </button>
                        </div>

                        <!-- Isi Popup -->
                        @php $witems = $wishlistItems ?? collect(); @endphp

                        @if ($witems->isEmpty())
                            <div class="text-center py-6">
                                <img src="/image/whislist.png" alt="kosong"
                                    class="mx-auto w-24 h-24 object-contain mb-2">
                                <h4 class="mt-1 text-base font-semibold text-black">Wishlist masih kosong!</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Tandai produk yang kamu suka biar nggak lupa.
                                </p>
                                <a href="{{ url('/shop') }}"
                                    class="inline-block mt-4 px-3 py-2 rounded border border-blue-500 text-blue-600 text-xs">
                                    Mulai isi Wishlist
                                </a>
                            </div>
                        @else
                            <div class="space-y-3 max-h-56 overflow-y-auto">
                                @foreach ($witems as $item)
                                    @php $product = $item->product; @endphp
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product && $product->foto ? Storage::url($product->foto) : '/image/agaicon.jpg' }}"
                                            alt="{{ $product->nama_produk ?? 'Produk' }}"
                                            class="w-12 h-12 object-cover rounded">
                                        <div class="flex-1">
                                            <p class="text-sm text-black font-semibold">
                                                {{ $product->nama_produk ?? 'Produk' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
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
                                                            $hargaDiskon =
                                                                $hargaAsli - $hargaAsli * ($discount->value / 100);
                                                        } elseif ($discount->discount_type === 'nominal') {
                                                            $hargaDiskon = max(0, $hargaAsli - $discount->value);
                                                        }
                                                    }
                                                @endphp

                                                @if ($discount)
                                                    <span class="text-gray-400 line-through mr-2">
                                                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                                    </span>
                                                    <span class=text-xs text-gray-500">
                                                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                                    </span>
                                                    <span class="ml-1 text-xs text-red-500">
                                                        Diskon :
                                                        ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">
                                                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-right mt-3">
                                @auth
                                    <a href="{{ url('/produk-favorit') }}" class="text-xs text-blue-600 hover:underline">
                                        Lihat Semua
                                    </a>
                                @else
                                    <button onclick="showLoginConfirmWishlistMobile()"
                                        class="text-xs text-blue-600 hover:underline">Lihat Semua</button>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>


                <!-- 🔹 Modal Konfirmasi Login (Mobile) -->
                <div id="loginConfirmWishlistMobile"
                    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-xs w-full text-center">
                        <h3 class="text-base font-semibold text-gray-800">Login Diperlukan</h3>
                        <p class="text-xs text-gray-600 mt-2">
                            Masuk dulu untuk melihat produk favoritmu.
                        </p>
                        <div class="flex justify-center gap-3 mt-4">
                            <a href="{{ route('Masuk') }}"
                                class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                                Masuk
                            </a>
                            <button onclick="hideLoginConfirmWishlistMobile()"
                                class="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg text-xs hover:bg-gray-100">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>


                <!-- 🔹 Cart Button -->
                <button onclick="togglePopup('cartPopupMobile')" class="focus:outline-none">
                    <span class="relative">
                        <i class="fas fa-cart-shopping text-xl cursor-pointer"></i>
                        @php $cc = $cartCount ?? 0; @endphp
                        <span id="cart-count"
                            class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-1 rounded-full {{ $cc == 0 ? 'opacity-60 scale-90' : '' }}">
                            {{ $cc }}
                        </span>
                    </span>
                </button>

                <!-- 🔹 Cart Popup (Mobile Version - Right Side like Wishlist) -->
                <div id="cartPopupMobile"
                    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden sm:hidden flex items-center justify-center"
                    onclick="closePopupOutside(event, 'cartPopupMobile')">


                    <div class="bg-white w-full max-w-xs rounded-lg shadow-lg p-4 max-h-[80vh] overflow-y-auto relative"
                        onclick="event.stopPropagation()">


                        <!-- Header -->
                        <div class="flex justify-between items-center border-b pb-2 mb-3">
                            <h3 class="text-sm text-black font-semibold">Keranjang</h3>
                            <button onclick="togglePopup('cartPopupMobile')"
                                class="text-gray-500 hover:text-red-500 text-lg font-bold">
                                ×
                            </button>
                        </div>

                        @php
                            $citems = $cartItems ?? collect();
                            $grandTotal = 0;
                            // group by product id supaya tampilan rapi (sumber tetap dipisah di bawah)
                            $grouped = $citems->groupBy('product_id');
                        @endphp

                        @if ($citems->isEmpty())
                            <!-- Keranjang kosong -->
                            <div class="text-center py-6">
                                <img src="/image/keranjang.png" alt="kosong"
                                    class="mx-auto w-24 h-24 object-contain mb-2">
                                <h4 class="mt-1 text-base font-semibold text-black">Wah, Keranjang belanjamu kosong!
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Yuk, mulai belanja sekarang dan temukan produk terbaik untukmu.
                                </p>
                                <a href="{{ url('/shop') }}"
                                    class="inline-block mt-4 px-3 py-2 rounded border border-blue-500 text-blue-600 text-xs">
                                    Mulai Belanja
                                </a>
                            </div>
                        @else
                            <!-- Isi keranjang -->
                            <div class="space-y-3 max-h-56 overflow-y-auto">
                                @foreach ($grouped as $productId => $items)
                                    @php
                                        $product = $items->first()->product;
                                        $foto =
                                            $product && $product->foto
                                                ? Storage::url($product->foto)
                                                : '/image/agaicon.jpg';
                                        $bySource = $items->groupBy('source');
                                    @endphp

                                    <div class="flex items-start gap-3">
                                        <img src="{{ $foto }}" alt="{{ $product->nama_produk ?? 'Produk' }}"
                                            class="w-12 h-12 object-cover rounded">
                                        <div class="flex-1">
                                            <p class="text-sm text-black font-semibold">
                                                {{ $product->nama_produk ?? 'Produk' }}
                                            </p>

                                            {{-- tampilkan tiap source (shop/pricelist) --}}
                                            @foreach ($bySource as $source => $list)
                                                @php
                                                    // jumlah untuk source ini
                                                    $qty = $list->sum('jumlah');
                                                    // subtotal untuk source ini berdasarkan cart.harga snapshot
                                                    $subtotal = $list->sum(function ($it) {
                                                        return ($it->harga ?? 0) * ($it->jumlah ?? 0);
                                                    });
                                                    $grandTotal += $subtotal;

                                                    // ambil display price (ambil pertama karena setiap row per source harus punya harga snapshot yang sama)
                                                    $displayPrice = $list->first()->harga ?? ($product->harga ?? 0);
                                                @endphp

                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $qty }}x |
                                                    <span class="font-semibold text-gray-700">Rp
                                                        {{ number_format($displayPrice, 0, ',', '.') }}</span>

                                                    {{-- penanda hanya untuk role tertentu --}}
                                                    @auth
                                                        @if (in_array(Auth::user()->role, ['client', 'admin', 'superadmin']))
                                                            <span
                                                                class="ml-1 text-[10px] px-2 py-0.5 rounded
                                                    {{ $source === 'pricelist' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                                                {{ $source === 'pricelist' ? 'Harga Pricelist' : 'Harga Normal' }}
                                                            </span>
                                                        @endif
                                                    @endauth
                                                </p>

                                                <p class="text-xs text-gray-600">Subtotal: Rp
                                                    {{ number_format($subtotal, 0, ',', '.') }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total akhir -->
                            <div class="text-right text-sm font-semibold text-green-600 mt-3">
                                Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </div>

                            <div class="text-right mt-2">
                                @auth
                                    <a href="{{ url('/keranjang') }}" class="text-xs text-blue-600 hover:underline">
                                        Lihat Semua
                                    </a>
                                @else
                                    <button onclick="showLoginConfirm()" class="text-xs text-blue-600 hover:underline">
                                        Lihat Semua
                                    </button>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Toggle -->
                <button class="text-white text-2xl focus:outline-none" onclick="toggleMobileMenu()">
                    ☰
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="w-full md:flex-1 relative flex order-3 md:order-none mt-3 md:mt-0">
            <input type="text" placeholder="Butuh sesuatu? Coba cari di sini..."
                onkeyup="filterSuggestions(this.value)"
                class="w-full px-4 py-2 rounded-l-md text-black focus:outline-none focus:ring-0" id="searchInput" />
            <button onclick="goSearch()" class="bg-green-500 px-4 py-2 rounded-r-md">
                <i class="fas fa-search text-white"></i>
            </button>
            <ul id="suggestions"
                class="absolute top-full left-0 w-full bg-white border border-gray-300 rounded-md mt-1 z-10 hidden shadow-md text-black max-h-48 overflow-y-auto">
            </ul>
        </div>

        <!-- Navbar kanan (desktop & tablet) -->
        <div class="hidden sm:flex flex-wrap justify-center md:justify-end items-center gap-2 text-sm relative z-50 w-full md:w-auto"
            id="desktopMenu">
            @guest
                <a href="/Masuk"><button
                        class="bg-white text-green-600 px-3 py-1 rounded w-full md:w-auto">Masuk</button></a>
                <a href="/Daftar"><button
                        class="bg-green-500 text-white px-3 py-1 rounded w-full md:w-auto">Daftar</button></a>
            @endguest

            @auth
                <a href="/profil"><button class="bg-blue-500 text-white px-3 py-1 rounded w-full md:w-auto">Profil
                        Saya</button></a>
                <form action="/logout" method="POST" class="inline w-full md:w-auto">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 text-white px-3 py-1 rounded w-full md:w-auto">Keluar</button>
                </form>
            @endauth
        </div>

        <!-- Navbar kanan (mobile menu) -->
        <div id="mobileMenu" class="sm:hidden hidden flex-col gap-2 mt-3 w-full">
            @guest
                <a href="/Masuk">
                    <button class="bg-white text-green-600 px-3 py-1 rounded w-full">Masuk</button>
                </a>
                <a href="/Daftar">
                    <button class="bg-green-500 text-white px-3 py-1 rounded w-full">Daftar</button>
                </a>
            @endguest

            @auth
                <a href="/profil">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded w-full">Profil Saya</button>
                </a>
                <form action="/logout" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded w-full">Keluar</button>
                </form>
            @endauth
        </div>

        {{-- WISHLIST --}}
        <div class="relative hidden sm:block">
            <button onclick="togglePopup('wishlistPopup')" class="focus:outline-none">
                <span class="relative">
                    <i class="far fa-heart text-xl cursor-pointer"></i>
                    @php $wc = $wishlistCount ?? 0; @endphp
                    <span id="wishlist-count"
                        class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] px-1 rounded-full {{ $wc == 0 ? 'opacity-60 scale-90' : '' }}">
                        {{ $wc }}
                    </span>
                </span>
            </button>
        </div>

        <div id="wishlistPopup"
            class="popup-panel hidden absolute right-0 top-full mt-2 w-80 bg-white border rounded-lg shadow-lg p-4 z-50">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-sm text-black font-semibold">Produk Favoritmu</h3>

                @auth
                    <a href="{{ url('/produk-favorit') }}" class="text-blue-600 text-xs hover:underline">Lihat</a>
                @else
                    <button onclick="showLoginConfirmWishlist()"
                        class="text-blue-600 text-xs hover:underline">Lihat</button>
                @endauth
            </div>

            @php $witems = $wishlistItems ?? collect(); @endphp

            @if ($witems->isEmpty())
                <div class="text-center py-6">
                    <img src="/image/whislist.png" alt="kosong" class="mx-auto w-36 h-36 object-contain mb-2">
                    <h4 class="mt-1 text-lg font-semibold text-black">Daftar Keinginanmu masih kosong!</h4>
                    <p class="text-xs text-gray-500 mt-1">Yuk, tandai produk yang kamu suka agar nggak lupa
                        nanti.</p>
                    <a href="{{ url('/shop') }}"
                        class="inline-block mt-4 px-4 py-2 rounded border border-blue-500 text-blue-600 text-sm">
                        Mulai isi Daftar Keinginanmu
                    </a>
                </div>
            @else
                <div class="space-y-3 max-h-56 overflow-y-auto">
                    @foreach ($witems as $item)
                        @php $product = $item->product; @endphp
                        <div class="flex items-center gap-3">
                            <img src="{{ $product && $product->foto ? Storage::url($product->foto) : '/image/agaicon.jpg' }}"
                                alt="{{ $product->nama_produk ?? 'Produk' }}" class="w-12 h-12 object-cover rounded">
                            <div class="flex-1">
                                <p class="text-sm text-black font-semibold">
                                    {{ $product->nama_produk ?? 'Produk' }}</p>
                                <p class="text-xs text-gray-500">
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
                                        <span class=text-xs text-gray-500">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-1 text-xs text-red-500">
                                            Diskon :
                                            ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-right mt-3">
                    @auth
                        <a href="{{ url('/produk-favorit') }}" class="text-sm text-blue-600 hover:underline">Lihat
                            Semua</a>
                    @else
                        <button onclick="showLoginConfirmWishlist()" class="text-sm text-blue-600 hover:underline">Lihat
                            Semua</button>
                    @endauth
                </div>
            @endif
        </div>

        {{-- Modal Konfirmasi Login untuk Wishlist --}}
        <div id="loginConfirmWishlist"
            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
                <h3 class="text-lg font-semibold text-gray-800">Login Diperlukan</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Anda harus Masuk terlebih dahulu untuk melihat daftar produk favoritmu.
                </p>
                <div class="flex justify-center gap-3 mt-4">
                    <a href="{{ route('Masuk') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Masuk
                    </a>
                    <button onclick="hideLoginConfirmWishlist()"
                        class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-100">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        {{-- CART --}}
        <div class="relative hidden sm:block">
            <button onclick="togglePopup('cartPopup')" class="focus:outline-none">
                <span class="relative">
                    <i class="fas fa-cart-shopping text-xl cursor-pointer"></i>
                    @php $cc = $cartCount ?? 0; @endphp
                    <span id="cart-count"
                        class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-1 rounded-full {{ $cc == 0 ? 'opacity-60 scale-90' : '' }}">
                        {{ $cc }}
                    </span>
                </span>
            </button>
        </div>

        <div id="cartPopup"
            class="popup-panel hidden absolute right-0 top-full mt-2 w-80 bg-white border rounded-lg shadow-lg p-4 z-50">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-sm text-black font-semibold">Keranjang</h3>

                @auth
                    <a href="{{ url('/keranjang') }}" class="text-blue-600 text-xs hover:underline">Lihat</a>
                @else
                    <button onclick="showLoginConfirm()" class="text-blue-600 text-xs hover:underline">Lihat</button>
                @endauth
            </div>

            @php
                $citems = $cartItems ?? collect();
                $grandTotal = 0;
                // group by product id supaya tampilan rapi (sumber tetap dipisah di bawah)
                $grouped = $citems->groupBy('product_id');
            @endphp

            @if ($grouped->isEmpty())
                <div class="text-center py-6">
                    <img src="/image/keranjang.png" alt="kosong" class="mx-auto w-24 h-24 object-contain mb-2">
                    <h4 class="mt-1 text-base font-semibold text-black">Wah, Keranjang belanjamu kosong!</h4>
                    <p class="text-xs text-gray-500 mt-1">Yuk, mulai belanja sekarang dan temukan produk terbaik
                        untukmu.</p>
                    <a href="{{ url('/shop') }}"
                        class="inline-block mt-4 px-3 py-2 rounded border border-blue-500 text-blue-600 text-xs">Mulai
                        Belanja</a>
                </div>
            @else
                <div class="space-y-3 max-h-56 overflow-y-auto">
                    @foreach ($grouped as $productId => $items)
                        @php
                            $product = $items->first()->product;
                            $foto = $product && $product->foto ? Storage::url($product->foto) : '/image/agaicon.jpg';
                            $bySource = $items->groupBy('source');
                        @endphp

                        <div class="flex items-start gap-3">
                            <img src="{{ $foto }}" alt="{{ $product->nama_produk ?? 'Produk' }}"
                                class="w-12 h-12 object-cover rounded">
                            <div class="flex-1">
                                <p class="text-sm text-black font-semibold">{{ $product->nama_produk ?? 'Produk' }}
                                </p>

                                {{-- tampilkan tiap source (shop/pricelist) --}}
                                @foreach ($bySource as $source => $list)
                                    @php
                                        // jumlah untuk source ini
                                        $qty = $list->sum('jumlah');
                                        // subtotal untuk source ini berdasarkan cart.harga snapshot
                                        $subtotal = $list->sum(function ($it) {
                                            return ($it->harga ?? 0) * ($it->jumlah ?? 0);
                                        });
                                        $grandTotal += $subtotal;

                                        // ambil display price (ambil pertama karena setiap row per source harus punya harga snapshot yang sama)
                                        $displayPrice = $list->first()->harga ?? ($product->harga ?? 0);
                                    @endphp

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $qty }}x |
                                        <span class="font-semibold text-gray-700">Rp
                                            {{ number_format($displayPrice, 0, ',', '.') }}</span>

                                        {{-- penanda hanya untuk role tertentu --}}
                                        @auth
                                            @if (in_array(Auth::user()->role, ['client', 'admin', 'superadmin']))
                                                <span
                                                    class="ml-1 text-[10px] px-2 py-0.5 rounded
                                                    {{ $source === 'pricelist' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $source === 'pricelist' ? 'Harga Pricelist' : 'Harga Normal' }}
                                                </span>
                                            @endif
                                        @endauth
                                    </p>

                                    <p class="text-xs text-gray-600">Subtotal: Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-right text-sm font-semibold text-green-600 mt-3">
                    Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </div>

                <div class="text-right mt-2">
                    @auth
                        <a href="{{ url('/keranjang') }}" class="text-sm text-blue-600 hover:underline">Lihat
                            Keranjang</a>
                    @else
                        <button onclick="showLoginConfirm()" class="text-sm text-blue-600 hover:underline">Lihat
                            Keranjang</button>
                    @endauth
                </div>
            @endif
        </div>

        {{-- Modal Konfirmasi Login --}}
        <div id="loginConfirmModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
                <h3 class="text-lg font-semibold text-gray-800">Login Diperlukan</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Anda harus Masuk terlebih dahulu untuk melihat keranjang.
                </p>
                <div class="flex justify-center gap-3 mt-4">
                    <a href="{{ route('Masuk') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Masuk
                    </a>
                    <button onclick="hideLoginConfirm()"
                        class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-100">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>


<script>
    function toggleMobileMenu() {
        document.getElementById("mobileMenu").classList.toggle("hidden");
    }
</script>

<!-- Notification Toast (tetap sama) -->
<div id="notification-toast" class="fixed top-5 right-5 z-50 space-y-3">
    @if (session('success'))
        <div
            class="bg-green-500 text-white px-4 py-3 rounded shadow-lg flex items-center justify-between min-w-[250px] animate-slide-in">
            <span class="text-sm">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-xl leading-none">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div
            class="bg-red-500 text-white px-4 py-3 rounded shadow-lg flex items-center justify-between min-w-[250px] animate-slide-in">
            <span class="text-sm">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-xl leading-none">&times;</button>
        </div>
    @endif
</div>

<script>
    function showLoginConfirm() {
        document.getElementById('loginConfirmModal').classList.remove('hidden');
        document.getElementById('loginConfirmModal').classList.add('flex');
    }

    function hideLoginConfirm() {
        document.getElementById('loginConfirmModal').classList.add('hidden');
        document.getElementById('loginConfirmModal').classList.remove('flex');
    }
</script>

<script>
    function showLoginConfirmWishlist() {
        document.getElementById('loginConfirmWishlist').classList.remove('hidden');
        document.getElementById('loginConfirmWishlist').classList.add('flex');
    }

    function hideLoginConfirmWishlist() {
        document.getElementById('loginConfirmWishlist').classList.add('hidden');
        document.getElementById('loginConfirmWishlist').classList.remove('flex');
    }
</script>



<script>
    const products = @json($allProducts);

    const input = document.getElementById("searchInput");
    const suggestions = document.getElementById("suggestions");

    function filterSuggestions(query) {
        suggestions.innerHTML = "";
        if (query.trim() === "") {
            suggestions.classList.add("hidden");
            return;
        }

        const filtered = products.filter(p =>
            p.nama_produk.toLowerCase().includes(query.toLowerCase()) ||
            p.slug.toLowerCase().includes(query.toLowerCase())
        ).slice(0, 8); // batasi max 8 hasil

        if (filtered.length === 0) {
            suggestions.classList.add("hidden");
            return;
        }

        filtered.forEach(p => {
            const li = document.createElement("li");
            li.textContent = p.nama_produk;
            li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer";
            li.onclick = () => {
                window.location.href = `/shop?search=${encodeURIComponent(p.slug)}`;
            };
            suggestions.appendChild(li);
        });

        suggestions.classList.remove("hidden");
    }

    function goSearch() {
        const query = input.value.trim();
        if (query !== "") {
            window.location.href = `/shop?search=${encodeURIComponent(query)}`;
        }
    }

    // enter key -> langsung search
    input.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            goSearch();
        }
    });
</script>
