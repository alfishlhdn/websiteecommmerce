@extends('Layouts.main')
@section('judul', 'Syarat & Ketentuan | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Syarat &amp; Ketentuan</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Syarat &amp; Ketentuan</h1>
                    {{-- <p class="text-sm text-gray-600 mt-1">Tanggal efektif: <span class="font-medium">22 Agustus 2025</span> --}}
                    </p>
                </header>

                {{-- Ringkasan singkat --}}
                <section class="mb-6">
                    <h2 class="text-lg font-medium text-gray-800">Ringkasan</h2>
                    <p class="text-gray-700 mt-2">
                        Dengan menggunakan situs dan layanan <strong>AGA IT COMPUTER</strong>, Anda menyetujui syarat &amp;
                        ketentuan ini.
                        Harap baca dengan cermat — jika Anda tidak setuju, jangan gunakan layanan kami.
                    </p>
                </section>

                {{-- Pasal --}}
                <section class="space-y-6 text-gray-700">
                    <div>
                        <h3 class="font-semibold text-gray-800">1. Definisi</h3>
                        <p class="mt-2 text-sm">
                            "Situs" berarti website AGA IT COMPUTER. "Pengguna" atau "Pelanggan" berarti setiap orang yang
                            mengakses dan/atau melakukan transaksi di situs.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">2. Harga & Ketersediaan</h3>
                        <ul class="list-disc ml-6 mt-2 space-y-1 text-sm">
                            <li>Harga yang tertera dapat berubah sewaktu-waktu tanpa pemberitahuan, terutama karena
                                kesalahan input, promo, atau fluktuasi supplier.</li>
                            <li>Stok bersifat dinamis. Pesanan akan dikonfirmasi setelah sistem atau tim kami memastikan
                                ketersediaan.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">3. Pemesanan & Pembayaran</h3>
                        <ul class="list-disc ml-6 mt-2 space-y-1 text-sm">
                            <li>Pesanan dianggap sah setelah pembayaran diverifikasi (untuk metode transfer) atau saat
                                konfirmasi pembayaran diterima.</li>
                            <li>Pilih metode pembayaran yang tersedia. Untuk transfer manual, unggah bukti transfer sesuai
                                instruksi.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">4. Pembatalan & Pengembalian</h3>
                        <ul class="list-disc ml-6 mt-2 space-y-1 text-sm">
                            <li>Pelanggan dapat membatalkan pesanan sebelum barang dikirim. Untuk pembatalan yang sudah
                                lewat kirim, ikuti prosedur retur.</li>
                            <li>Pengembalian barang karena cacat/kerusakan harus dilaporkan segera, disertai bukti foto dan
                                nomor pesanan.</li>
                            <li>Kebijakan retur dan garansi dapat berbeda antar produk — periksa halaman produk untuk
                                detail.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">5. Pengiriman</h3>
                        <p class="mt-2 text-sm">
                            Waktu pengiriman adalah estimasi yang bergantung pada kurir, alamat tujuan, dan ketersediaan
                            barang. Nomor resi akan dibagikan setelah paket dikirim.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">6. Garansi & Layanan</h3>
                        <p class="mt-2 text-sm">
                            Garansi produk mengikuti ketentuan pabrikan atau ketentuan toko (jika diberi garansi toko).
                            Untuk klaim garansi layanan servis, simpan bukti service & nota.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">7. Tanggung Jawab</h3>
                        <p class="mt-2 text-sm">
                            AGA IT COMPUTER tidak bertanggung jawab atas keterlambatan pengiriman yang disebabkan oleh pihak
                            ketiga (kurir), force majeure, atau informasi pengiriman yang tidak lengkap dari pelanggan.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">8. Penggunaan Akun</h3>
                        <ul class="list-disc ml-6 mt-2 space-y-1 text-sm">
                            <li>Pengguna bertanggung jawab menjaga keamanan akses perangkat dan sesi login. Semua aktivitas
                                pada akun menjadi tanggung jawab pemilik akun.</li>
                            <li>Kami berhak menangguhkan atau menutup akun yang melakukan pelanggaran atau aktivitas curang.
                            </li>
                            <li><strong>Jika menggunakan login Google (SSO),</strong> pengelolaan sandi dan pemulihan akses
                                dilakukan melalui <em>akun Google</em> Anda, bukan melalui sistem kami.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">9. Hak Kekayaan Intelektual</h3>
                        <p class="mt-2 text-sm">
                            Semua materi di situs (teks, gambar, logo, layout) adalah milik AGA IT COMPUTER atau mitra
                            terkait dan dilindungi oleh undang-undang hak cipta.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">10. Perubahan Syarat</h3>
                        <p class="mt-2 text-sm">
                            Kami dapat memperbarui syarat &amp; ketentuan ini kapan saja. Perubahan akan dipublikasikan di
                            situs dengan tanggal revisi. Penggunaan situs setelah perubahan berarti Anda menerima syarat
                            terbaru.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">11. Hukum yang Berlaku & Penyelesaian Sengketa</h3>
                        <p class="mt-2 text-sm">
                            Syarat ini diatur oleh hukum Republik Indonesia. Sengketa yang timbul akan diupayakan
                            diselesaikan secara musyawarah; jika tidak tercapai, diselesaikan melalui jalur hukum yang
                            berlaku.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">12. Kontak</h3>
                        <p class="mt-2 text-sm">
                            Untuk pertanyaan mengenai syarat &amp; ketentuan, silakan hubungi:
                        </p>
                        <ul class="list-none ml-0 mt-2 text-sm text-gray-700 space-y-1">
                            <li><strong>WA / Telp:</strong> <a href="https://wa.me/6281333892111"
                                    class="text-blue-600 hover:underline">+62 813-3389-2111</a></li>
                            <li><strong>Email:</strong> <a href="mailto:agakomputer25@gmail.com"
                                    class="text-blue-600 hover:underline">agakomputer25@gmail.com</a></li>
                            <li><strong>Alamat:</strong> Jl. Watu Gede, Ruko Chandra Kirana, Watugede, Singosari — Malang
                            </li>
                        </ul>
                    </div>

                    {{-- PASAL BARU: LOGIN GOOGLE & RESET AKUN --}}
                    <div>
                        <h3 class="font-semibold text-gray-800">13. Login Google &amp; Pemulihan Akun</h3>
                        <ul class="list-disc ml-6 mt-2 space-y-2 text-sm">
                            <li><strong>Kebijakan Login:</strong> Untuk alasan keamanan dan kemudahan, situs ini dapat
                                mewajibkan atau merekomendasikan login menggunakan <em>Akun Google yang aktif</em> (Single
                                Sign-On/SSO).</li>
                            <li><strong>Pemulihan Akses:</strong> Jika Anda lupa kata sandi, pemulihan dilakukan melalui
                                fitur <em>“Lupa Sandi”</em> milik Google (accounts.google.com). Kami tidak menyimpan kata
                                sandi Google Anda dan tidak dapat meresetnya.</li>
                            <li><strong>Persyaratan Akun Google:</strong> Pengguna wajib memastikan Akun Google dalam
                                kondisi aktif, dapat menerima kode verifikasi (mis. email/telepon), dan tidak melanggar
                                kebijakan Google.</li>
                            <li><strong>Data yang Kami Terima dari Google:</strong> Saat Anda login via Google, kami dapat
                                menerima data dasar seperti nama, alamat email, dan foto profil untuk keperluan
                                pembuatan/otentikasi akun di situs.</li>
                            <li><strong>Keamanan:</strong> Aktifkan verifikasi 2 langkah (2FA) pada Akun Google untuk
                                perlindungan tambahan. Kehilangan akses terhadap Akun Google berpotensi menyebabkan
                                hilangnya akses ke akun situs.</li>
                            <li><strong>Alternatif Login:</strong> Jika/ketika login email & kata sandi lokal tersedia,
                                prosedur reset akan dikirim ke email yang terdaftar di sistem kami. Detailnya akan
                                diinformasikan pada halaman login.</li>
                        </ul>

                        <div class="mt-3 rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-blue-900">
                            <strong>Catatan:</strong> Demi keamanan, kami tidak pernah meminta kata sandi Google Anda.
                            Selalu periksa domain login resmi Google saat diminta autentikasi.
                        </div>
                    </div>
                </section>

                {{-- CTA kecil --}}
                <section class="mt-6">
                    <div
                        class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Butuh klarifikasi?</div>
                            <div class="text-sm text-blue-100">Tim customer service kami siap membantu.</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="mailto:agakomputer25@gmail.com"
                                class="inline-flex items-center rounded-md bg-white text-blue-700 px-4 py-2 font-medium">Email
                                Kami</a>
                            <a href="https://wa.me/6281333892111" target="_blank"
                                class="inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-white">Chat
                                WA</a>
                        </div>
                    </div>
                </section>

                <p class="mt-3 text-xs text-gray-400">Catatan: Versi ringkas ditampilkan di sini — untuk informasi hukum
                    lengkap, gunakan dokumen resmi perusahaan.</p>
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description"
        content="Syarat & Ketentuan AGA IT COMPUTER — ketentuan pemesanan, pembayaran, pengiriman, retur, garansi, dan login Google untuk pemulihan akun.">
    <meta property="og:title" content="Syarat & Ketentuan | AGA IT COMPUTER">
    <meta property="og:description"
        content="Baca syarat & ketentuan penggunaan layanan, termasuk kebijakan login Google & pemulihan akun di AGA IT COMPUTER.">
    <meta property="og:url" content="{{ url()->current() }}">
@endpush
