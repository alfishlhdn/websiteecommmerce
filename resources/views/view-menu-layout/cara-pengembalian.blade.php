@extends('Layouts.main')
@section('judul', 'Pengembalian & Garansi | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Pengembalian &amp; Garansi</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Pengembalian &amp; Garansi</h1>
                    <p class="text-sm text-gray-600 mt-1">Tanggal efektif: <span class="font-medium">22 Agustus 2025</span>
                    </p>
                </header>

                <section class="text-gray-700 space-y-4">
                    <p>
                        Kami berkomitmen mengirimkan produk dalam kondisi baik. Jika Anda menerima barang yang rusak,
                        tidak sesuai pesanan, atau membutuhkan klaim layanan, berikut panduan singkat untuk proses klaim dan
                        garansi.
                    </p>

                    {{-- Kriteria Pengembalian --}}
                    <div class="rounded-xl border bg-gray-50 p-4">
                        <h2 class="font-semibold text-gray-800">Kriteria Pengembalian</h2>
                        <ul class="list-disc ml-6 mt-3 text-sm text-gray-700 space-y-2">
                            <li><strong>Kerusakan saat diterima:</strong> (fisik/komponen) — Laporkan dalam <strong>1×24
                                    jam</strong> sejak penerimaan dengan foto/video bukti dan nomor pesanan.</li>
                            <li><strong>Barang tidak sesuai pesanan:</strong> (salah model/varian) — Laporkan segera untuk
                                proses penggantian.</li>
                            <li><strong>Perubahan pikiran:</strong> Pengembalian karena bukan kesalahan kami mengikuti
                                kebijakan retur khusus dan mungkin dikenakan biaya kirim.</li>
                        </ul>
                    </div>

                    {{-- Prosedur Klaim --}}
                    <div>
                        <h2 class="font-semibold text-gray-800">Prosedur Klaim Pengembalian / Garansi</h2>
                        <ol class="list-decimal ml-6 mt-3 text-sm text-gray-700 space-y-2">
                            <li>Hubungi CS melalui <a href="https://wa.me/6281333892111"
                                    class="text-blue-600 hover:underline">WhatsApp</a>, telepon, atau email. Sertakan nomor
                                pesanan, deskripsi masalah, dan bukti (foto/video).</li>
                            <li>Tim kami akan melakukan verifikasi awal dan memberikan instruksi pengembalian (jika
                                diperlukan) termasuk alamat pengembalian dan siapa menanggung ongkir sesuai kasus.</li>
                            <li>Setelah barang kami terima dan evaluasi selesai, kami akan proses pengembalian dana,
                                penggantian, atau perbaikan sesuai keputusan klaim dan ketersediaan stok.</li>
                        </ol>

                        <p class="mt-3 text-sm text-gray-600">
                            Estimasi waktu penanganan klaim: <strong>3–14 hari kerja</strong> tergantung jenis klaim dan
                            waktu evaluasi teknis.
                        </p>
                    </div>

                    {{-- Garansi Layanan --}}
                    <div>
                        <h2 class="font-semibold text-gray-800">Garansi Layanan</h2>
                        <p class="text-sm text-gray-700 mt-2">
                            Untuk layanan servis, kami memberikan garansi pekerjaan sesuai ketentuan yang disepakati saat
                            serah terima (contoh: 7–30 hari tergantung jenis perbaikan). Detail garansi akan tercantum pada
                            nota/kwitansi service.
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Syarat garansi servis meliputi bukti service, nota, dan tidak
                            berlaku jika kerusakan disebabkan oleh kesalahan penggunaan setelah servis.</p>
                    </div>

                    {{-- Biaya Kirim untuk Retur --}}
                    <div>
                        <h2 class="font-semibold text-gray-800">Biaya Pengiriman Retur</h2>
                        <p class="text-sm text-gray-700 mt-2">
                            Penanggung biaya pengiriman retur tergantung kasus:
                        </p>
                        <ul class="list-disc ml-6 mt-2 text-sm text-gray-700 space-y-1">
                            <li>Jika klaim disetujui karena kerusakan/kerusakan pengiriman atau kesalahan kami — ongkir
                                retur ditanggung kami.</li>
                            <li>Jika pengembalian karena perubahan pikiran — ongkir retur menjadi tanggungan pelanggan,
                                kecuali ada kebijakan promosi khusus.</li>
                        </ul>
                    </div>

                    {{-- Form & Dokumen --}}
                    <div class="rounded-xl border p-4 bg-white">
                        <h2 class="font-semibold text-gray-800">Form Permohonan Klaim</h2>
                        <p class="text-sm text-gray-700 mt-2">Untuk mempercepat proses, silakan isi form klaim dan lampirkan
                            bukti yang diminta.</p>
                        <div class="mt-3 flex flex-wrap gap-3">
                            {{-- Ganti link di bawah sesuai rute nyata di aplikasimu --}}
                            <a href="/return-form"
                                class="inline-flex items-center gap-2 rounded-md bg-blue-600 text-white px-4 py-2 font-medium hover:bg-blue-700">Isi
                                Form Klaim</a>
                            <a href="/download/claim-template.pdf"
                                class="inline-flex items-center gap-2 rounded-md border px-4 py-2 hover:bg-gray-50">Unduh
                                Template Klaim (PDF)</a>
                        </div>
                    </div>

                    {{-- FAQ --}}
                    <section class="mt-4">
                        <h3 class="font-semibold text-gray-800">FAQ Singkat</h3>
                        <div class="mt-3 divide-y text-sm text-gray-700">
                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Berapa lama proses klaim sampai selesai?
                                </summary>
                                <p class="mt-2">Setelah barang diterima untuk evaluasi, estimasi penyelesaian adalah 3–14
                                    hari kerja tergantung kompleksitas kerusakan.</p>
                            </details>

                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Apa yang harus saya siapkan saat mengajukan
                                    klaim?</summary>
                                <p class="mt-2">Nomor pesanan, foto/video kerusakan, bukti pembelian/nota, dan deskripsi
                                    singkat masalah.</p>
                            </details>

                            <details class="py-3">
                                <summary class="cursor-pointer font-medium">Apakah garansi berlaku untuk komponen internal?
                                </summary>
                                <p class="mt-2">Garansi produk mengikuti ketentuan pabrikan dan jenis komponen. Untuk
                                    garansi toko/servis, akan diinformasikan saat serah terima.</p>
                            </details>
                        </div>
                    </section>

                    {{-- CTA --}}
                    <div class="mt-6">
                        <div
                            class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex items-center justify-between">
                            <div>
                                <div class="font-semibold">Butuh Bantuan Klaim?</div>
                                <div class="text-sm text-blue-100">Hubungi tim CS kami untuk panduan langkah demi langkah.
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="mailto:info@agaitcomputer.id"
                                    class="inline-flex items-center rounded-md bg-white text-blue-700 px-4 py-2 font-medium">Kirim
                                    Email</a>
                                <a href="https://wa.me/6281333892111" target="_blank"
                                    class="inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-white">Chat
                                    WA</a>
                            </div>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-gray-400">Catatan: Ketentuan di atas adalah ringkasan. Untuk informasi hukum
                        atau klaim khusus, gunakan dokumen resmi perusahaan atau hubungi layanan pelanggan.</p>
                </section>
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description"
        content="Panduan pengembalian & garansi AGA IT COMPUTER — prosedur klaim, kriteria pengembalian, garansi layanan, dan form klaim.">
    <meta property="og:title" content="Pengembalian & Garansi | AGA IT COMPUTER">
    <meta property="og:description"
        content="Pelajari cara mengajukan klaim, syarat retur, dan garansi servis. Hubungi +62 813-3389-2111 untuk bantuan.">
    <meta property="og:url" content="{{ url()->current() }}">
@endpush
