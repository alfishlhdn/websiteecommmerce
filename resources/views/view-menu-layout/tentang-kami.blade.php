@extends('Layouts.main')
@section('judul', 'Tentang Kami | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">

        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumbs --}}
            <nav class="text-sm text-gray-500 mb-3" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Tentang Kami</li>
                </ol>
            </nav>

            {{-- Header / Hero Strip --}}
            <section class="rounded-2xl border shadow bg-gradient-to-r from-blue-50 via-white to-blue-50 p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <img src="/image/logoagaitcomputer.png" alt="AGA IT COMPUTER"
                        class="w-28 h-28 object-contain rounded-xl bg-white border shadow-sm">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">AGA IT COMPUTER</h1>
                        <p class="mt-1 text-gray-600">Toko komputer & layanan servis laptop/komputer terpercaya di
                            Singosari, Kab. Malang. Produk berkualitas, servis profesional, dan garansi transparan.</p>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center gap-2 rounded-full border bg-white px-3 py-1 text-xs md:text-sm">
                                <span class="text-green-600">●</span> Bergaransi Resmi
                            </span>
                            <span
                                class="inline-flex items-center gap-2 rounded-full border bg-white px-3 py-1 text-xs md:text-sm">
                                🔧 Teknisi Berpengalaman
                            </span>
                            <span
                                class="inline-flex items-center gap-2 rounded-full border bg-white px-3 py-1 text-xs md:text-sm">
                                🧾 Struk & Invoice
                            </span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a href="https://wa.me/6281333892111" target="_blank"
                            class="inline-flex items-center justify-center rounded-xl bg-green-600 hover:bg-green-700 text-white px-4 py-3 font-medium shadow">
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </section>


            {{-- Tentang, Visi, Misi --}}
            <section class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 rounded-2xl border bg-white p-6 shadow">
                    <h2 class="text-lg font-semibold mb-2">Tentang Kami</h2>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>AGA IT COMPUTER</strong> adalah penyedia solusi perangkat keras dan layanan servis
                        laptop/komputer yang berlokasi di Singosari, Kab. Malang.
                        Sejak berdiri, kami berkomitmen menghadirkan produk original/berkualitas, layanan perbaikan yang
                        akurat, dan dukungan purna jual yang jelas serta transparan.
                    </p>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl bg-blue-50 border p-4">
                            <h3 class="font-semibold">Visi</h3>
                            <p class="text-gray-700 mt-1">
                                Menjadi pilihan utama pelanggan di Singosari, Kab. Malang, dan sekitarnya untuk kebutuhan
                                perangkat IT yang terpercaya dan bernilai.
                            </p>
                        </div>
                        <div class="rounded-xl bg-blue-50 border p-4">
                            <h3 class="font-semibold">Misi</h3>
                            <ul class="list-disc pl-5 text-gray-700 mt-1 space-y-1">
                                <li>Menyediakan produk & suku cadang asli/berkualitas dengan harga kompetitif.</li>
                                <li>Memberikan layanan servis cepat, akurat, dan transparan.</li>
                                <li>Membangun kepercayaan melalui komunikasi jujur & garansi layanan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Mengapa Memilih Kami --}}
                <aside class="rounded-2xl border bg-white p-6 shadow">
                    <h3 class="text-lg font-semibold mb-3">Mengapa Memilih Kami?</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex gap-3"><span>✅</span> Barang bergaransi resmi & bergaransi toko</li>
                        <li class="flex gap-3"><span>⚡</span> Diagnosa cepat, estimasi biaya di awal</li>
                        <li class="flex gap-3"><span>🧰</span> Sparepart berkualitas & tools profesional</li>
                        <li class="flex gap-3"><span>🔒</span> Data pelanggan dijaga kerahasiaannya</li>
                        <li class="flex gap-3"><span>📦</span> Tersedia antar-jemput (area tertentu)</li>
                    </ul>
                </aside>
            </section>

            {{-- Layanan Utama --}}
            <section class="mt-6 rounded-2xl border bg-white p-6 shadow">
                <h3 class="text-lg font-semibold mb-4">Layanan & Produk</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-xl border p-4">
                        <div class="font-semibold">Penjualan Perangkat</div>
                        <p class="text-sm text-gray-600">Laptop, PC, komponen, periferal, dan aksesoris.</p>
                    </div>
                    <div class="rounded-xl border p-4">
                        <div class="font-semibold">Servis Laptop/PC</div>
                        <p class="text-sm text-gray-600">Upgrade, perbaikan hardware, thermal, instalasi OS & software.</p>
                    </div>
                    <div class="rounded-xl border p-4">
                        <div class="font-semibold">Konsultasi & Rakitan</div>
                        <p class="text-sm text-gray-600">Rakit PC sesuai kebutuhan kerja, gaming, kreatif, atau kantor.</p>
                    </div>
                </div>
            </section>
            
            {{-- FAQ Accordion --}}
            <section class="mt-6 rounded-2xl border bg-white p-6 shadow" x-data>
                <h3 class="text-lg font-semibold mb-4">FAQ</h3>
                <div class="divide-y">
                    <details class="group py-3">
                        <summary class="flex cursor-pointer items-center justify-between text-sm font-medium text-gray-800">
                            Apakah servis bergaransi?
                            <span class="ml-3 text-gray-400 group-open:rotate-180 transition">⌄</span>
                        </summary>
                        <p class="mt-2 text-sm text-gray-600">Ya. Garansi layanan mengikuti ketentuan jenis perbaikan & part
                            yang digunakan.</p>
                    </details>
                    <details class="group py-3">
                        <summary class="flex cursor-pointer items-center justify-between text-sm font-medium text-gray-800">
                            Apakah ada opsi antar-jemput perangkat?
                            <span class="ml-3 text-gray-400 group-open:rotate-180 transition">⌄</span>
                        </summary>
                        <p class="mt-2 text-sm text-gray-600">Tersedia untuk area tertentu. Silakan hubungi admin untuk
                            ketersediaan & biaya.</p>
                    </details>
                    <details class="group py-3">
                        <summary class="flex cursor-pointer items-center justify-between text-sm font-medium text-gray-800">
                            Metode pembayaran yang diterima?
                            <span class="ml-3 text-gray-400 group-open:rotate-180 transition">⌄</span>
                        </summary>
                        <p class="mt-2 text-sm text-gray-600">Transfer bank, QRIS, dan tunai di toko. E-wallet dapat
                            ditanyakan ke admin.</p>
                    </details>
                </div>
            </section>

            {{-- Kontak & Lokasi --}}
            <section class="mt-6 rounded-2xl border bg-white p-6 shadow">
                <h3 class="text-lg font-semibold mb-3">Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-xl border p-4">
                        <div class="text-sm text-gray-600">Telp/WA</div>
                        <a href="https://wa.me/6281333892111" target="_blank"
                            class="font-medium text-blue-600 hover:underline">+62 813-3389-2111</a>
                    </div>
                    <div class="rounded-xl border p-4">
                        <div class="text-sm text-gray-600">Alamat</div>
                        <a href="https://maps.app.goo.gl/9ewVePwYTtSK8eSD7" target="_blank"
                            class="font-medium text-blue-600 hover:underline">
                            Jl. Watu Gede, Ruko Chandra Kirana, Watugede – Singosari, Malang
                        </a>
                    </div>
                    <div class="rounded-xl border p-4">
                        <div class="text-sm text-gray-600">Jam Operasional</div>
                        <div class="font-medium">Senin–Sabtu, 08:00–17:00</div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section class="mt-6">
                <div class="rounded-2xl border bg-gradient-to-r from-blue-600 to-blue-500 p-6 md:p-8 text-white shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold">Butuh Bantuan Cepat?</h3>
                            <p class="text-blue-100">Konsultasi gratis untuk pilihan produk, rakitan PC, atau jadwal
                                servis.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://wa.me/6281333892111" target="_blank"
                                class="inline-flex items-center justify-center rounded-xl bg-white text-blue-700 px-4 py-3 font-semibold shadow hover:bg-blue-50">
                                Chat Sekarang
                            </a>
                            <a href="/shop"
                                class="inline-flex items-center justify-center rounded-xl border border-white/70 px-4 py-3 font-semibold hover:bg-white/10">
                                Jelajahi Produk
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <p class="mt-3 text-xs text-gray-400">**Estimasi tergantung antrian & jenis kerusakan.</p>
        </main>
    </div>
@endsection

@push('meta')
    {{-- Meta SEO (opsional, pastikan layout punya @stack('meta')) --}}
    <meta name="description"
        content="AGA IT COMPUTER — Toko komputer & service laptop di Singosari, Malang. Produk bergaransi, servis profesional, dan konsultasi rakit PC. Hubungi +62 813-3389-2111.">
    <meta property="og:title" content="Tentang Kami | AGA IT COMPUTER">
    <meta property="og:description"
        content="Toko komputer & service laptop terpercaya di Malang. Produk berkualitas, servis cepat, bergaransi.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('image/agaitcomputer.png') }}">

    {{-- JSON-LD Organization --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ElectronicsStore",
      "name": "AGA IT COMPUTER",
      "image": "{{ asset('image/agaitcomputer.png') }}",
      "url": "{{ url('/') }}",
      "telephone": "+62 813-3389-2111",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Watu Gede, Ruko Chandra Kirana, Watugede - Singosari",
        "addressLocality": "Malang",
        "addressRegion": "Jawa Timur",
        "addressCountry": "ID"
      },
      "openingHoursSpecification": [{
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
        "opens": "08:00",
        "closes": "17:00"
      }],
      "sameAs": [
        "https://maps.app.goo.gl/9ewVePwYTtSK8eSD7"
      ]
    }
    </script>

    {{-- JSON-LD FAQPage (sesuaikan jika perlu) --}}
    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"FAQPage",
      "mainEntity":[
        {
          "@type":"Question",
          "name":"Apakah servis bergaransi?",
          "acceptedAnswer":{"@type":"Answer","text":"Ya. Garansi layanan mengikuti ketentuan jenis perbaikan & part yang digunakan."}
        },
        {
          "@type":"Question",
          "name":"Apakah ada opsi antar-jemput perangkat?",
          "acceptedAnswer":{"@type":"Answer","text":"Tersedia untuk area tertentu. Silakan hubungi admin untuk ketersediaan & biaya."}
        },
        {
          "@type":"Question",
          "name":"Metode pembayaran yang diterima?",
          "acceptedAnswer":{"@type":"Answer","text":"Transfer bank, QRIS, dan tunai di toko. E-wallet dapat ditanyakan ke admin."}
        }
      ]
    }
    </script>
@endpush
