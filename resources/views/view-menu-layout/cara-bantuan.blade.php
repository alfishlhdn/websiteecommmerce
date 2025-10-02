@extends('Layouts.main')
@section('judul', 'Bantuan & Dukungan | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Bantuan &amp; Dukungan</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Bantuan &amp; Dukungan</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Tim Customer Service kami siap membantu mulai dari pra-pembelian hingga purna-jual.
                    </p>
                </header>

                {{-- Kontak --}}
                <section class="mt-4">
                    <h2 class="font-semibold text-gray-800">Cara Menghubungi</h2>
                    <ul class="list-disc ml-6 mt-3 space-y-2 text-gray-700 text-sm">
                        <li>
                            WhatsApp:
                            <a href="https://wa.me/6281333892111" target="_blank"
                                class="text-blue-600 hover:underline font-medium">
                                +62 813-3389-2111
                            </a>
                            <span class="text-gray-500">(respon cepat pada jam operasional)</span>
                        </li>
                        <li>
                            Kunjungi kami di:
                            <a href="https://maps.app.goo.gl/9ewVePwYTtSK8eSD7" target="_blank"
                                class="text-blue-600 hover:underline font-medium">
                                Jl. Watu Gede, Ruko Chandra Kirana, Watugede – Singosari, Malang
                            </a>
                        </li>
                    </ul>
                </section>

                {{-- FAQ singkat --}}
                <section class="mt-6">
                    <h2 class="font-semibold text-gray-800">Pertanyaan Umum</h2>
                    <p class="text-sm text-gray-700 mt-2">
                        Untuk pertanyaan terkait ketersediaan stok, estimasi perbaikan, atau panduan produk,
                        sertakan detail produk (merek / model) saat menghubungi kami agar tim dapat memberikan jawaban
                        lebih cepat dan akurat.
                    </p>
                </section>

                {{-- Jam Operasional --}}
                <section class="mt-6">
                    <h2 class="font-semibold text-gray-800">Jam Operasional</h2>
                    <div class="mt-3 rounded-xl border bg-gray-50 p-4 text-sm text-gray-700">
                        <ul class="space-y-1">
                            <li><span class="font-medium">Senin – Sabtu:</span> 09.00 – 17.00 WIB</li>
                            <li><span class="font-medium">Minggu &amp; Hari Libur:</span> Tutup</li>
                        </ul>
                        <p class="mt-2 text-gray-500">
                            Pesan atau pertanyaan di luar jam operasional akan kami tanggapi pada hari kerja berikutnya.
                        </p>
                    </div>
                </section>

                {{-- CTA --}}
                <div class="mt-6">
                    <div
                        class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Masih butuh bantuan?</div>
                            <div class="text-sm text-blue-100">Hubungi Customer Service kami sekarang.</div>
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
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description"
        content="Hubungi AGA IT COMPUTER untuk bantuan & dukungan seputar pembelian, perbaikan, atau garansi. Tersedia via WhatsApp, email, dan kunjungan toko di Malang.">
    <meta property="og:title" content="Bantuan & Dukungan | AGA IT COMPUTER">
    <meta property="og:description"
        content="Customer Service AGA IT COMPUTER siap membantu pra-pembelian hingga purna-jual. Hubungi +62 813-3389-2111 atau kunjungi toko kami di Malang.">
    <meta property="og:url" content="{{ url()->current() }}">
@endpush
