@extends('Layouts.main')
@section('judul', 'Pengiriman | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Pengiriman</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Informasi Pengiriman</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Kami mengirimkan pesanan menggunakan ekspedisi pihak ketiga seperti
                        <strong>JNE, J&T, SiCepat, dan Wahana</strong>.
                        Saat ini semua pengiriman hanya menggunakan layanan <strong>Reguler</strong>.
                        Karena kami tidak memiliki kerja sama khusus dengan ekspedisi, ongkos kirim dapat sedikit lebih mahal dibandingkan marketplace.
                    </p>
                </header>

                <section class="text-gray-700">
                    <div class="rounded-xl border p-4 bg-gray-50 mb-6">
                        <h3 class="font-semibold text-gray-800">Jenis Layanan</h3>
                        <ul class="list-disc ml-5 mt-2 text-sm text-gray-700 space-y-1">
                            <li><strong>Reguler:</strong> Estimasi 2–7 hari kerja (tergantung lokasi tujuan & ekspedisi).</li>
                        </ul>
                    </div>

                    <div class="rounded-xl border p-4 bg-gray-50 mb-6">
                        <h3 class="font-semibold text-gray-800">Ekspedisi yang Digunakan</h3>
                        <ul class="list-disc ml-5 mt-2 text-sm text-gray-700 space-y-1">
                            <li>JNE (Reguler)</li>
                            <li>J&T Express (Reguler)</li>
                            <li>SiCepat (Reguler)</li>
                            <li>Wahana (Reguler)</li>
                        </ul>
                        <p class="text-xs text-gray-500 mt-2">Catatan: Tidak tersedia opsi Same Day, Instant, atau Cargo.</p>
                    </div>

                    <div class="rounded-xl border p-4 bg-gray-50 mb-6">
                        <h3 class="font-semibold text-gray-800">Biaya & Perhitungan</h3>
                        <p class="text-sm text-gray-700 mt-2">
                            Ongkos kirim dihitung berdasarkan berat/volume produk dan alamat tujuan.
                            Estimasi biaya akan tampil saat checkout.
                            Karena tanpa kerja sama khusus dengan ekspedisi, biaya bisa sedikit lebih tinggi dibanding harga di marketplace.
                        </p>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800">Pelacakan & Nomor Resi</h3>
                        <p class="text-sm text-gray-700 mt-2">
                            Setelah paket dikirim, nomor resi akan kami tampilkan di halaman detail pesanan di menu pesanan yang ada diprofil ,
                            Silakan gunakan nomor resi tersebut untuk melacak status pengiriman di website resmi ekspedisi.
                        </p>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800">Penerimaan & Pemeriksaan Paket</h3>
                        <p class="text-sm text-gray-700 mt-2">
                            Saat menerima paket, mohon periksa kondisi kemasan dan barang.
                            Jika ada kerusakan atau segel terbuka, segera catat pada bukti terima kurir dan laporkan ke tim kami maksimal 24 jam setelah penerimaan.
                        </p>
                    </div>

                    {{-- FAQ singkat --}}
                    <section class="mt-6">
                        <h4 class="font-semibold text-gray-900">Pertanyaan Umum</h4>
                        <div class="mt-3 divide-y text-sm text-gray-700">
                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Bagaimana jika paket terlambat?</summary>
                                <p class="mt-2">Silakan lacak status resi terlebih dahulu. Jika lebih dari 3 hari tidak ada update, hubungi tim CS kami untuk bantuan klaim ke ekspedisi.</p>
                            </details>
                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Apakah bisa kirim ke kantor/indekos?</summary>
                                <p class="mt-2">Bisa, asalkan alamat jelas dan mudah dijangkau kurir. Sertakan nomor telepon penerima agar mempermudah pengantaran.</p>
                            </details>
                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Bisakah memilih ekspedisi tertentu?</summary>
                                <p class="mt-2">Bisa, selama ekspedisi tersedia di tujuan Anda. Pilihan ekspedisi bisa ditentukan saat proses checkout.</p>
                            </details>
                        </div>
                    </section>

                    {{-- CTA --}}
                    <div class="mt-6">
                        <div class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex items-center justify-between">
                            <div>
                                <div class="font-semibold">Ada pertanyaan seputar pengiriman?</div>
                                <div class="text-sm text-blue-100">Hubungi tim CS kami untuk bantuan lebih lanjut.</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="mailto:info@agaitcomputer.id"
                                   class="inline-flex items-center rounded-md bg-white text-blue-700 px-4 py-2 font-medium">Email</a>
                                <a href="https://wa.me/6281333892111" target="_blank"
                                   class="inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-white">Chat WA</a>
                            </div>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-gray-400">
                        Catatan: Estimasi & biaya pengiriman dapat berubah sewaktu-waktu tergantung kebijakan ekspedisi dan kondisi operasional.
                    </p>
                </section>
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description" content="Informasi pengiriman AGA IT COMPUTER — menggunakan ekspedisi JNE, J&T, SiCepat, Wahana (hanya reguler). Estimasi waktu, biaya, dan prosedur pelacakan.">
    <meta property="og:title" content="Pengiriman | AGA IT COMPUTER">
    <meta property="og:description" content="Semua pengiriman menggunakan layanan reguler JNE, J&T, SiCepat, Wahana. Cek estimasi biaya dan prosedur pelacakan di sini.">
    <meta property="og:url" content="{{ url()->current() }}">
@endpush
