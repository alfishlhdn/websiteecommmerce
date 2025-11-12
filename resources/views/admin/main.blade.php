<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('judul')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/image/logoagaitcomputer.png" type="image/png" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .flex {
            margin-top: -1%;
        }

        .navbar {
            margin-top: 0%;
            margin-left: 240px;
            width: 88%;
        }

        .aside {
            margin-top: -4%;
            height: 1000%;
        }

        .navbar img {
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <!-- Navbar -->
    <nav class="navbar fixed top-0 w-[200] z-50 bg-blue-900 text-white flex justify-end px-6 py-4">
        <button id="profileButton" class="flex items-center gap-2 relative focus:outline-none">
            <span class="sr-only">Buka menu profil pengguna</span>
            <img src="{{ asset('image/agaitcomputer.png') }}" alt="Foto Profil Pengguna"
                class="w-10 h-10 rounded-full border-2 border-gray-300 hover:border-indigo-500 transition-colors duration-200 cursor-pointer" />
        </button>

        <div id="profileDropdown"
            class="absolute right-6 top-16 bg-white shadow-xl rounded-lg overflow-hidden hidden z-50 w-64 transform origin-top-right transition-all duration-300 ease-in-out scale-95 opacity-0 p-6">

            <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                <img src="{{ asset('image/logoagaitcomputer.png') }}" alt="Foto Profil"
                    class="w-12 h-12 rounded-full border-2 border-gray-300 mr-4" />
                <div>
                    <p class="font-bold text-lg text-gray-900">{{ Auth::user()->name ?? 'Nama User' }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email ?? 'email@contoh.com' }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('profile') }}"
                    class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-gray-700">Profil Saya</span>
                </a>
            </div>


            <div class="my-4 border-t border-gray-200"></div>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 w-full p-2 rounded-md text-red-600 hover:bg-red-50 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="flex">
        @php
            $produkActive = request()->is('produk*') || request()->is('brand*') || request()->is('kategori*');
            $transaksiActive = request()->is('pesanan*') || request()->is('pembayaran*') || request()->is('kurir*');
            $laporanActive = request()->is('laporan*') || request()->is('pelanggan*') || request()->is('ulasan*');
            $inventoriActive = request()->is('stok*') || request()->is('cart*') || request()->is('whislist*');
        @endphp

        <aside class="fixed inset-y-0 left-0 top-0 z-40 w-60 bg-blue-900 text-white shadow-lg flex flex-col">
            <!-- Header Logo (Sticky) -->
            <div class="sticky top-0 bg-blue-900 z-50 flex flex-col items-center p-4 border-b border-white">
                <img src="{{ asset('image/agaitcomputer.png') }}" alt="Logo"
                    class="w-20 h-20 object-cover rounded-full mb-2">
                <h1 class="text-lg font-bold text-center">AGA IT COMPUTER</h1>
            </div>

            <!-- Menu (Scrollable) -->
            <ul class="space-y-3 text-sm px-4 py-4 overflow-y-auto h-full mt-6">
                <!-- Dashboard -->
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-2 hover:bg-blue-700 px-3 py-2 rounded {{ request()->is('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>

                <!-- Manajemen Produk -->
                <li>
                    <button type="button"
                        class="w-full flex items-center justify-between px-3 py-2 hover:bg-blue-700 rounded submenu-toggle">
                        <span class="flex items-center gap-2"><i class="fas fa-boxes"></i> Manajemen Produk</span>
                        <i
                            class="fas fa-chevron-down transition-transform duration-300 transform {{ $produkActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <ul class="ml-6 mt-1 space-y-2 submenu {{ $produkActive ? '' : 'hidden' }}">
                        <li><a href="/produk"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('produk*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-box"></i> Produk</a></li>
                        <li><a href="/brand"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('brand*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-tags"></i> Brand</a></li>
                        <li><a href="/kategori"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('kategori*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-list"></i> Kategori</a></li>

                    </ul>
                </li>

                <!-- Manajemen Diskon -->
                <li>
                    <a href="/discounts"
                        class="flex items-center gap-2 hover:bg-blue-700 px-3 py-2 rounded {{ request()->is('discounts*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-percent"></i> Manajemen Diskon
                    </a>
                </li>


                <!-- Transaksi -->
                <li>
                    <button type="button"
                        class="w-full flex items-center justify-between px-3 py-2 hover:bg-blue-700 rounded submenu-toggle">
                        <span class="flex items-center gap-2"><i class="fas fa-cash-register"></i> Transaksi</span>
                        <i
                            class="fas fa-chevron-down transition-transform duration-300 transform {{ $transaksiActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <ul class="ml-6 mt-1 space-y-2 submenu {{ $transaksiActive ? '' : 'hidden' }}">
                        <li><a href="{{ url('/pesanan') }}"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('pesanan*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-shopping-cart"></i> Pesanan</a></li>
                        <li><a href="{{ url('/kurir') }}"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('kurir*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-shipping-fast"></i> Kurir</a></li>
                        <li><a href="{{ url('/metode-pembayaran') }}"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('metode-pembayaran*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-wallet"></i> Metode Pembayaran</a></li>
                        <li><a href="{{ route('riwayat-transaksi.index') }}"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('riwayat-transaksi*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-history"></i> Riwayat Transaksi</a></li>
                    </ul>
                </li>


                <!-- Laporan & Pelanggan -->
                <li>
                    <button type="button"
                        class="w-full flex items-center justify-between px-3 py-2 hover:bg-blue-700 rounded submenu-toggle">
                        <span class="flex items-center gap-2"><i class="fas fa-clipboard-list"></i> Laporan &
                            Pelanggan</span>
                        <i
                            class="fas fa-chevron-down transition-transform duration-300 transform {{ $laporanActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <ul class="ml-6 mt-1 space-y-2 submenu {{ $laporanActive ? '' : 'hidden' }}">
                        <li><a href="/laporan"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('laporan*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-file-alt"></i> Laporan Penjualan</a></li>
                        <li><a href="/pelanggan"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('pelanggan*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-users"></i> Pelanggan</a></li>
                        <li><a href="/ulasan"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('ulasan*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-comment-dots"></i> Ulasan</a></li>
                    </ul>
                </li>

                <!-- Inventori -->
                <li>
                    <button type="button"
                        class="w-full flex items-center justify-between px-3 py-2 hover:bg-blue-700 rounded submenu-toggle">
                        <span class="flex items-center gap-2"><i class="fas fa-warehouse"></i> Inventori</span>
                        <i
                            class="fas fa-chevron-down transition-transform duration-300 transform {{ $inventoriActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <ul class="ml-6 mt-1 space-y-2 submenu {{ $inventoriActive ? '' : 'hidden' }}">
                        <li><a href="/stok"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('stok*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-warehouse"></i> Stok</a></li>
                        <li><a href="/cart"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('cart*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-shopping-basket"></i> Keranjang</a></li>
                        <li><a href="/whislist"
                                class="block px-3 py-2 rounded hover:bg-blue-700 {{ request()->is('whislist*') ? 'bg-blue-700' : '' }}"><i
                                    class="fas fa-heart"></i> Wishlist</a></li>
                    </ul>
                </li>

                <!-- Analitik -->
                <li><a href="/analitik"
                        class="flex items-center gap-2 hover:bg-blue-700 px-3 py-2 rounded {{ request()->is('analitik*') ? 'bg-blue-700' : '' }}"><i
                            class="fas fa-chart-pie"></i> Analitik Pengunjung</a></li>

                <!-- Pengaturan Toko -->
                <li><a href="/pengaturan-toko"
                        class="flex items-center gap-2 hover:bg-blue-700 px-3 py-2 rounded {{ request()->is('pengaturan-toko*') ? 'bg-blue-700' : '' }}"><i
                            class="fas fa-cogs"></i> Pengaturan Toko</a></li>

                <!-- Superadmin -->
                @if (auth()->user()->role == 'superadmin')
                    <li><a href="/user-role"
                            class="flex items-center gap-2 hover:bg-blue-700 px-3 py-2 rounded {{ request()->is('user-role*') ? 'bg-blue-700' : '' }}"><i
                                class="fas fa-user-shield"></i> Manajemen User</a></li>
                @endif

                <!-- Logout -->
                <li>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button
                            class="flex w-full items-center gap-2 bg-red-600 hover:bg-red-700 px-3 py-2 rounded text-white mt-4">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>


        <!-- Main Content -->
        <main id="main-content" class="ml-60 mt-16 p-6 w-full min-h-[calc(100vh-4rem)]">
            @yield('content')
        </main>
    </div>

    <!-- 🔔 Notification Toast -->
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
        document.addEventListener('DOMContentLoaded', () => {
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');

            profileButton.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');

                if (!profileDropdown.classList.contains('hidden')) {
                    profileDropdown.classList.remove('scale-95', 'opacity-0');
                    profileDropdown.classList.add('scale-100', 'opacity-100');
                } else {
                    profileDropdown.classList.remove('scale-100', 'opacity-100');
                    profileDropdown.classList.add('scale-95', 'opacity-0');
                }
            });

            document.addEventListener('click', (e) => {
                if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    if (!profileDropdown.classList.contains('hidden')) {
                        profileDropdown.classList.add('hidden');
                        profileDropdown.classList.remove('scale-100', 'opacity-100');
                        profileDropdown.classList.add('scale-95', 'opacity-0');
                    }
                }
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggles = document.querySelectorAll('.submenu-toggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const submenu = toggle.nextElementSibling;
                    const icon = toggle.querySelector('i.fas.fa-chevron-down');

                    submenu.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180'); // animasi panah balik
                });
            });
        });
    </script>


</body>

</html>
