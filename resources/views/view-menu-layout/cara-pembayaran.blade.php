@extends('Layouts.main')
@section('judul', 'Pembayaran | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Pembayaran</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Metode Pembayaran</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Semua transaksi di AGA IT COMPUTER dapat dilakukan melalui <strong>QRIS</strong> maupun <strong>transfer bank</strong>.
                        Pilih metode pembayaran yang paling nyaman untuk Anda.
                    </p>
                </header>

                {{-- QRIS --}}
                <section class="mb-6">
                    <h2 class="font-semibold text-gray-800">Pembayaran via QRIS</h2>
                    <p class="text-gray-700 mt-1">
                        QR Code akan otomatis ditampilkan saat <em>checkout</em> maupun di halaman detail pesanan.
                    </p>

                    <ol class="list-decimal ml-6 mt-3 space-y-1 text-sm">
                        <li>Selesaikan proses checkout hingga ke halaman pembayaran.</li>
                        <li>Pindai QRIS dengan aplikasi bank atau dompet digital Anda.</li>
                        <li>Pastikan nominal sesuai dengan total tagihan.</li>
                        <li>Lanjutkan pembayaran hingga transaksi berhasil.</li>
                        <li>Sistem kami akan otomatis memverifikasi pembayaran Anda.</li>
                    </ol>
                </section>

                {{-- Transfer Bank --}}
                <section class="mb-6">
                    <h2 class="font-semibold text-gray-800">Pembayaran via Transfer Bank</h2>
                    <p class="text-gray-700 mt-1">
                        Jika Anda memilih pembayaran dengan transfer bank, Anda akan mendapatkan nomor rekening tujuan atau virtual account saat checkout.
                    </p>

                    <ol class="list-decimal ml-6 mt-3 space-y-1 text-sm">
                        <li>Pilih metode <strong>Transfer Bank</strong> di halaman checkout.</li>
                        <li>Catat nomor rekening / virtual account yang ditampilkan.</li>
                        <li>Lakukan transfer sesuai nominal tagihan.</li>
                        <li>Untuk rekening manual, unggah bukti transfer agar diproses oleh admin.</li>
                        <li>Untuk virtual account, sistem akan otomatis mengenali pembayaran Anda.</li>
                    </ol>

                </section>

                {{-- Keamanan --}}
                <section class="mb-6">
                    <h2 class="font-semibold text-gray-800">Keamanan Transaksi</h2>
                    <p class="mt-2 text-gray-700 text-sm">
                        Semua metode pembayaran kami bersifat <strong>aman</strong>, <strong>praktis</strong>, dan <strong>real-time</strong>.
                        Untuk transfer manual, verifikasi dilakukan setelah bukti pembayaran diterima.
                    </p>
                    <ul class="list-disc ml-6 mt-2 text-sm text-gray-700 space-y-1">
                        <li>Gunakan aplikasi perbankan atau dompet digital resmi.</li>
                        <li>Pastikan nama penerima sesuai: <em>AGA KOMPUTER</em>.</li>
                        <li>Jangan membagikan bukti pembayaran ke pihak yang tidak dikenal.</li>
                    </ul>
                </section>

                {{-- FAQ singkat --}}
                <section>
                    <h2 class="font-semibold text-gray-800">Pertanyaan Umum</h2>
                    <div class="mt-2 space-y-3 text-sm">
                        <div>
                            <p class="font-medium">❓ Bagaimana jika pembayaran saya gagal?</p>
                            <p class="text-gray-700">Coba ulangi pembayaran atau gunakan aplikasi e-wallet lain. Jika masih gagal, hubungi tim CS kami.</p>
                        </div>
                        <div>
                            <p class="font-medium">❓ Apakah ada biaya tambahan?</p>
                            <p class="text-gray-700">Tidak, pelanggan tidak dikenakan biaya tambahan selain total belanja yang tertera.</p>
                        </div>
                        <div>
                            <p class="font-medium">❓ Berapa lama pesanan saya diproses?</p>
                            <p class="text-gray-700">Pesanan akan langsung diproses setelah pembayaran terverifikasi dalam sistem.</p>
                        </div>
                    </div>
                </section>

                {{-- CTA Kontak --}}
                <section class="mt-6">
                    <div class="rounded-xl border bg-gradient-to-r from-green-600 to-green-500 p-4 text-white flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Ada kendala pembayaran?</div>
                            <div class="text-sm text-green-100">Hubungi tim CS kami untuk bantuan lebih lanjut.</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="mailto:agakomputer25@gmail.com"
                               class="inline-flex items-center rounded-md bg-white text-green-700 px-4 py-2 font-medium">Email</a>
                            <a href="https://wa.me/6281333892111" target="_blank"
                               class="inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-white">Chat WA</a>
                        </div>
                    </div>
                </section>
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description" content="Pembayaran di AGA IT COMPUTER aman dan cepat dengan QRIS dan transfer bank. Panduan cara bayar, keamanan, dan FAQ tersedia di sini.">
    <meta property="og:title" content="Metode Pembayaran | AGA IT COMPUTER">
    <meta property="og:description" content="Semua transaksi di AGA IT COMPUTER menggunakan QRIS dan transfer bank. Baca panduan pembayaran dan keamanan transaksi.">
    <meta property="og:url" content="{{ url()->current() }}">
@endpush
