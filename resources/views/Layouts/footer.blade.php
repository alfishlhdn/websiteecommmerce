@php
    $store = App\Models\Store_Setiing::first();
@endphp

<footer class="relative bg-white pt-10 pb-6 text-black border-t border-gray-300 overflow-hidden">
    <!-- Background Watermark -->
    <div class="absolute right-0 top-0 h-full opacity-10 hidden md:block">
        <img src="/image/logoagaitcomputer.png" alt="Background" class="h-full object-contain">
    </div>

    <!-- Logo & Info -->
    <div
        class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-sm mb-6 relative z-10 gap-6 md:gap-0">
        <!-- Logo -->
        <div class="flex items-center gap-3 text-center md:text-left">
            <img src="/image/logoagaitcomputer.png" alt="Logo" class="w-14 h-14 rounded-full mx-auto md:mx-0">
            <div>
                <p class="font-bold">{{ $store->store_name ?? 'AGA COMPUTER' }}</p>
                <p class="text-xs">SOLUSI IT DAN PC TERPERCAYA</p>
            </div>
        </div>

        <!-- Jam & Alamat -->
        <div class="text-center md:text-right space-y-1">
            <p><i class="fas fa-clock mr-1"></i> Senin - Sabtu : 08.00 - 20.00</p>
            <p>
                <i class="fas fa-map-marker-alt mr-1"></i>

                @if (!empty($store->address_ingooglemaps) && !empty($store->address))
                    <a href="{{ $store->address_ingooglemaps }}" target="_blank" class="hover:underline text-blue-600">
                        {{ $store->address }}
                    </a>
                @else
                    <span class="text-gray-600">Alamat belum tersedia</span>
                @endif
            </p>

            <!-- Sosmed -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end items-center gap-2 mt-4">
                <p class="font-medium">Ikuti Kami :</p>
                <div class="flex gap-3 items-center">
                    <a href="https://vt.tiktok.com/ZSSdtJas4/" target="_blank">
                        <img src="/image/tiktokshop.png" alt="TikTok Shop" class="w-8 h-6">
                    </a>
                    <a href="https://www.facebook.com/share/18Vyg2igY1/" target="_blank">
                        <img src="/image/facebook.png" alt="Facebook" class="w-5 h-5">
                    </a>
                    <a href="https://www.instagram.com/agaitcomputer" target="_blank">
                        <img src="/image/instagram.webp" alt="Instagram" class="w-10 h-8 rounded-full">
                    </a>
                    <a href="https://tokopedia.com/aga-it-computer" target="_blank">
                        <img src="/image/tokopedia.png" alt="Tokopedia" class="w-6 h-6">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6 relative z-10">

    <!-- Grid Utama -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 px-4 text-sm relative z-10">
        <!-- Tentang -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Tentang Toko</h3>
            <p class="text-justify">
                {{ $store->store_name ?? 'AGA IT COMPUTER ' }} adalah toko komputer terpercaya di Malang yang
                menyediakan berbagai layanan dan produk IT.
                Kami melayani penjualan laptop, PC rakitan, aksesoris, serta jasa service & instalasi software.
                Berlokasi di {{ $store->address ?? 'TEST' }}, kami hadir untuk kebutuhan personal maupun bisnis Anda.
            </p>
        </div>

        <!-- Informasi -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Informasi</h3>
            <p><strong>Email:</strong> <a href="mailto:{{ $store->email ?? 'agakomputer' }}"
                    class="underline">{{ $store->email ?? 'agakomputer' }}</a></p>
            <p><strong>Phone:</strong> <a href="tel:{{ $store->phone ?? 'AGA COMPUTER' }}"
                    class="underline">{{ $store->phone ?? 'AGA COMPUTER' }}</a></p>
            <div class="mt-3 space-y-1">
                <a href="/tentang-kami" class="hover:underline block">Tentang Kami</a>
                <a href="/cara-belanja" class="hover:underline block">Cara Belanja</a>
                <a href="/cara-pembayaran" class="hover:underline block">Cara Pembayaran</a>
                <a href="/metode-pengiriman" class="hover:underline block">Metode Pengiriman</a>
                <a href="/cara-pengembalian" class="hover:underline block">Cara Pengembalian</a>
                <a href="/kebijakan-privasi" class="hover:underline block">Kebijakan Privasi</a>
                <a href="/syarat-ketentuan" class="hover:underline block">Syarat & Ketentuan</a>
                <a href="/pusat-bantuan" class="hover:underline block">Pusat Bantuan</a>
            </div>
        </div>

        <!-- Layanan -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Layanan</h3>
            <ul class="space-y-1">
                <li>Service Komputer & Laptop</li>
                <li>Install Ulang OS</li>
                <li>Upgrade RAM / SSD</li>
                <li>Backup & Recovery Data</li>
                <li>Rakit PC Custom</li>
                <li>Konsultasi IT</li>
            </ul>
        </div>

        <!-- Pembayaran & Pengiriman -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Pembayaran & Pengiriman</h3>

            <!-- Pengiriman -->
            <p class="font-semibold mb-2">📦 Pengiriman</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <img src="/image/jne.png" alt="JNE" class="max-h-10 mx-auto">
                <img src="/image/sicepat.png" alt="SiCepat" class="max-h-10 mx-auto">
                <img src="/image/wahana.png" alt="Wahana" class="max-h-10 mx-auto">
                <img src="/image/jntexpresss.png" alt="J&T" class="max-h-10 mx-auto">
            </div>

            <!-- Pembayaran -->
            <p class="font-semibold mb-2">💳 Pembayaran</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <img src="/image/Qris.png" alt="QRIS" class="max-h-10 mx-auto">
                <img src="/image/bank_transfer.png" alt="Bank Transfer" class="max-h-10 mx-auto">
            </div>
        </div>
    </div>

    <!-- Credit -->
    <div class="text-center text-xs mt-8 text-gray-600 relative z-10">
        &copy; 2025 AGA IT COMPUTER.
    </div>
</footer>

<!-- WhatsApp Floating -->
<a href="https://wa.me/6281333892111?text=Halo%20Admin%2C%20saya%20ingin%20tanya%20tentang%20produk" target="_blank"
    class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-full shadow-lg z-50 flex items-center space-x-2">
    <i class="fab fa-whatsapp text-xl"></i>
    <span class="hidden md:block font-semibold">Hubungi Kami</span>
</a>
