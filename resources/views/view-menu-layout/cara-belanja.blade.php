@extends('Layouts.main')
@section('judul', 'Cara Belanja | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Cara Belanja</li>
                </ol>
            </nav>

            {{-- Card utama --}}
            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Cara Belanja di AGA IT COMPUTER</h1>
                    <p class="text-sm text-gray-600 mt-1">Mudah, aman, dan transparan — panduan singkat untuk menyelesaikan
                        pesanan Anda.</p>
                </header>

                {{-- Langkah Belanja --}}
                <section class="mt-4 space-y-4">
                    <ol class="space-y-4">

                        {{-- Step 1: Login --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                1
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Login</h3>
                                <p class="text-sm text-gray-600">
                                    Pastikan Anda sudah login terlebih dahulu. Jika belum login, Anda hanya bisa
                                    melihat-lihat produk
                                    tanpa bisa membeli.
                                </p>
                            </div>
                        </li>

                        {{-- Step 2: Pilih Produk --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                2
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Pilih Produk</h3>
                                <p class="text-sm text-gray-600">
                                    Pilih produk dari <span class="font-medium">beranda</span> atau klik
                                    <span class="font-medium">Lihat Semua</span> untuk ke halaman <span
                                        class="font-medium">Shop</span>.
                                    Anda bisa menekan gambar produk atau tombol <span class="font-medium">Lihat</span> untuk
                                    melihat detailnya.
                                    Di halaman detail produk akan tersedia informasi lengkap: deskripsi, spesifikasi, brand,
                                    dan kategori.
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    Dari sini Anda bisa menambahkan ke <span class="font-medium">Keranjang</span>,
                                    memasukkan ke <span class="font-medium">Wishlist</span>, atau langsung menekan
                                    <span class="font-medium">Beli Sekarang</span>.
                                </p>
                            </div>
                        </li>

                        {{-- Step 3: Keranjang --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                3
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Periksa Keranjang</h3>
                                <p class="text-sm text-gray-600">
                                    Buka halaman keranjang untuk memeriksa jumlah produk, varian, dan harga total.
                                    Anda bisa memilih salah satu produk dengan mencentang kotak atau pilih semua produk
                                    sekaligus.
                                    Setelah dipilih, tekan tombol <span class="font-medium text-green-600">Checkout</span>
                                    di bagian kiri.
                                </p>
                            </div>
                        </li>

                        {{-- Step 4: Checkout --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                4
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Checkout</h3>
                                <p class="text-sm text-gray-600">
                                    Isi <span class="font-medium">alamat lengkap</span> agar pengiriman tepat waktu.
                                    Pilih kurir yang tersedia, masukkan catatan khusus (misalnya warna/ukuran), dan pastikan
                                    jumlah pembelian sesuai.
                                    Jika ada <span class="font-medium">voucher</span>, gunakan sebelum melanjutkan
                                    pembayaran.
                                </p>
                            </div>
                        </li>

                        {{-- Step 5: Pembayaran --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                5
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Pembayaran</h3>
                                <p class="text-sm text-gray-600">
                                    Pilih metode pembayaran: <span class="font-medium">QRIS</span>,
                                    <span class="font-medium">Bank Transfer</span> (BCA, Mandiri, dll), atau
                                    <span class="font-medium">E-Wallet</span>. Setelah menekan <span
                                        class="font-medium">Bayar Sekarang</span>,
                                    pop-up pembayaran akan muncul sesuai metode yang dipilih.
                                </p>
                            </div>
                        </li>

                        {{-- Step 6: Konfirmasi --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                6
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Konfirmasi</h3>
                                <p class="text-sm text-gray-600">
                                    Upload bukti pembayaran di halaman konfirmasi. Jika salah mengunggah bukti, pesanan akan
                                    dipending.
                                    Pesanan hanya diproses setelah pembayaran terverifikasi.
                                </p>
                            </div>
                        </li>

                        {{-- Step 7: Proses & Pengiriman --}}
                        <li class="flex gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-full bg-blue-50 border flex items-center justify-center text-blue-600 font-semibold">
                                7
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Proses & Pengiriman</h3>
                                <p class="text-sm text-gray-600">
                                    Setelah pembayaran dikonfirmasi, pesanan diproses dan Anda akan menerima nomor resi.
                                    Status pesanan bisa dicek di <span class="font-medium">Profil → Pesanan Saya</span>.
                                    Jika ingin membatalkan sebelum barang dikirim, gunakan tombol <span
                                        class="font-medium">Batalkan</span>.
                                </p>
                            </div>
                        </li>

                    </ol>
                </section>


                {{-- Tips singkat --}}
                <section class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl border p-4 bg-gray-50">
                        <h4 class="font-semibold text-gray-800 text-sm">Tips Sebelum Checkout</h4>
                        <ul class="mt-2 text-sm text-gray-600 list-disc ml-5 space-y-1">
                            <li>Periksa deskripsi produk & garansi sebelum membeli.</li>
                            <li>Bandingkan varian & harga jika tersedia.</li>
                            <li>Gunakan alamat lengkap (kode pos) untuk menghindari keterlambatan.</li>
                        </ul>
                    </div>
                    <div class="rounded-xl border p-4 bg-gray-50">
                        <h4 class="font-semibold text-gray-800 text-sm">Metode Pembayaran</h4>
                        <p class="text-sm text-gray-600 mt-2">Kami menerima transfer bank, QRIS, dan pembayaran di toko.
                            Untuk pertanyaan metode lain, hubungi CS.</p>
                    </div>
                </section>

                {{-- FAQ singkat --}}
                <section class="mt-6">
                    <h4 class="font-semibold text-gray-900">Pertanyaan Umum</h4>
                    <div class="mt-3 divide-y">
                        <details class="py-3">
                            <summary class="cursor-pointer font-medium">Berapa lama proses pengecekan after-sales?</summary>
                            <p class="mt-2 text-sm text-gray-600">Estimasi tergantung jenis kerusakan; umumnya 1–3 hari
                                kerja. Informasi lengkap akan kami sampaikan setelah diagnosa.</p>
                        </details>
                        <details class="py-3">
                            <summary class="cursor-pointer font-medium">Bagaimana jika barang rusak saat diterima?</summary>
                            <p class="mt-2 text-sm text-gray-600">Segera hubungi kami lewat WhatsApp.</p>
                        </details>
                    </div>
                </section>

                {{-- CTA --}}
                <section class="mt-6">
                    <div
                        class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <div class="font-semibold">Siap berbelanja?</div>
                            <div class="text-sm text-blue-100">Jelajahi produk kami dan dapatkan penawaran terbaik.</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="/shop"
                                class="inline-flex items-center justify-center rounded-md bg-white text-blue-700 px-4 py-2 font-medium hover:bg-white/90">Jelajahi
                                Produk</a>
                            <a href="https://wa.me/6281333892111" target="_blank"
                                class="inline-flex items-center justify-center rounded-md border border-white/30 px-4 py-2 text-white hover:bg-white/10">Tanya
                                CS</a>
                        </div>
                    </div>
                </section>

                <p class="mt-3 text-xs text-gray-400">Catatan: Stok & estimasi pengiriman dapat berubah tergantung
                    ketersediaan dan lokasi pengiriman.</p>
            </article>
        </main>
    </div>
@endsection
