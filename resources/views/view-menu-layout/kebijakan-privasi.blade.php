@extends('Layouts.main')
@section('judul', 'Kebijakan Privasi | AGA IT COMPUTER — Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        @include('Layouts.sidebarhelp')

        <main class="md:col-span-3">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1">
                    <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
                    <li aria-hidden="true" class="px-1">/</li>
                    <li class="text-gray-700 font-medium">Kebijakan Privasi</li>
                </ol>
            </nav>

            <article class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
                <header class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">Kebijakan Privasi</h1>
                    {{-- <p class="text-sm text-gray-600 mt-1">Diperbarui: <span class="font-medium">22 Agustus 2025</span> — --}}
                        Baca untuk memahami bagaimana kami mengumpulkan, menggunakan, dan melindungi data Anda.</p>
                </header>

                {{-- Ringkasan singkat --}}
                <section class="mb-6">
                    <h2 class="text-lg font-medium text-gray-800">Ringkasan Singkat</h2>
                    <p class="text-gray-700 mt-2 leading-relaxed">
                        Kami menghargai privasi Anda. Data yang kami kumpulkan digunakan untuk memproses pesanan,
                        meningkatkan layanan, dan berkomunikasi dengan Anda. Kami tidak menjual informasi pribadi Anda ke
                        pihak ketiga.
                    </p>
                </section>

                {{-- Detail kebijakan --}}
                <section class="space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800">1. Data yang Kami Kumpulkan</h3>
                        <ul class="list-disc ml-6 mt-2 text-gray-700 space-y-1">
                            <li>Informasi identitas: nama lengkap, alamat pengiriman, alamat penagihan, nomor telepon.</li>
                            <li>Informasi akun & kontak: email, kata sandi (tersimpan aman/bukan plaintext).</li>
                            <li>Data transaksi: produk yang dibeli, tanggal pembelian, metode pembayaran, riwayat pesanan.
                            </li>
                            <li>Interaksi layanan pelanggan: pesan, keluhan, atau bukti transfer yang Anda kirimkan.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">2. Tujuan & Dasar Hukum Penggunaan Data</h3>
                        <p class="text-gray-700 mt-2 leading-relaxed">
                            Kami menggunakan data Anda untuk:
                        </p>
                        <ul class="list-disc ml-6 mt-2 text-gray-700 space-y-1">
                            <li>Memproses dan mengirimkan pesanan.</li>
                            <li>Memberikan layanan purna jual dan garansi.</li>
                            <li>Komunikasi terkait pembaruan pesanan, promosi, dan informasi penting.</li>
                            <li>Meningkatkan pengalaman belanja melalui analitik dan perbaikan layanan.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">3. Penggunaan Cookie & Teknologi Serupa</h3>
                        <p class="text-gray-700 mt-2">
                            Situs ini menggunakan cookie untuk menyimpan preferensi, keranjang belanja sementara, dan
                            analitik anonim. Anda dapat menonaktifkan cookie melalui pengaturan browser, namun beberapa
                            fitur mungkin menjadi terbatas.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">4. Pengungkapan ke Pihak Ketiga</h3>
                        <p class="text-gray-700 mt-2 leading-relaxed">
                            Kami dapat berbagi data dengan:
                        </p>
                        <ul class="list-disc ml-6 mt-2 text-gray-700 space-y-1">
                            <li>Penyedia layanan pengiriman untuk proses pengiriman paket.</li>
                            <li>Penyedia pembayaran (bank/QRIS) untuk verifikasi transaksi.</li>
                            <li>Penyedia layanan pihak ketiga (mis. sistem email, analytics) yang membantu pengoperasian
                                situs — semuanya dengan perjanjian kerahasiaan.</li>
                        </ul>
                        <p class="text-gray-600 mt-2 text-sm">Kami tidak menjual data pelanggan kepada pihak ketiga untuk
                            tujuan pemasaran tanpa persetujuan eksplisit.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">5. Keamanan Data</h3>
                        <p class="text-gray-700 mt-2 leading-relaxed">
                            Kami menerapkan langkah-langkah teknis dan operasional (mis. enkripsi saat transmisi, kontrol
                            akses internal, dan backup terjadwal) untuk melindungi data dari akses tidak sah. Namun, tidak
                            ada sistem yang 100% aman — jika terjadi insiden, kami akan menindaklanjuti sesuai kebijakan.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">6. Retensi Data</h3>
                        <p class="text-gray-700 mt-2">
                            Kami menyimpan data transaksi dan data akun selama diperlukan untuk memenuhi kewajiban hukum,
                            menyelesaikan klaim, atau untuk tujuan bisnis internal seperti analitik. Jika Anda ingin data
                            Anda dihapus, hubungi kami (lihat bagian Kontak).
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">7. Hak Pengguna</h3>
                        <p class="text-gray-700 mt-2 leading-relaxed">
                            Anda berhak untuk:
                        </p>
                        <ul class="list-disc ml-6 mt-2 text-gray-700 space-y-1">
                            <li>Mengakses data pribadi Anda.</li>
                            <li>Memperbaiki data jika ada kesalahan.</li>
                            <li>Meminta penghapusan, pembatasan pemrosesan, atau menolak pemrosesan untuk tujuan tertentu.
                            </li>
                            <li>Menarik persetujuan kapan saja (jika pemrosesan berbasis persetujuan).</li>
                        </ul>
                        <p class="text-sm text-gray-600 mt-2">Untuk menggunakan hak di atas, hubungi tim kami melalui kontak
                            resmi.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">8. Perubahan Kebijakan</h3>
                        <p class="text-gray-700 mt-2">
                            Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Perubahan signifikan akan diumumkan di
                            situs dan/atau diberitahukan melalui email bila relevan. Tanggal pembaruan akan tercantum di
                            bagian atas halaman ini.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">9. Kontak</h3>
                        <p class="text-gray-700 mt-2">
                            Untuk pertanyaan privasi, pengajuan hak akses data, atau permintaan penghapusan, silakan
                            hubungi:
                        </p>
                        <ul class="list-none ml-0 mt-2 text-gray-700 space-y-1">
                            <li><strong>WA / Telp:</strong> <a href="https://wa.me/6281333892111"
                                    class="text-blue-600 hover:underline">+62 813-3389-2111</a></li>
                            <li><strong>Email:</strong> <a href="mailto:agakomputer25@gmail.com"
                                    class="text-blue-600 hover:underline">agakomputer25@gmail.com</a> (jika belum ada,
                                ganti dengan email resmi)</li>
                            <li><strong>Alamat:</strong> Jl. Watu Gede, Ruko Chandra Kirana, Watugede, Singosari — Malang
                            </li>
                        </ul>
                    </div>
                </section>

                {{-- CTA kecil --}}
                <section class="mt-6">
                    <div
                        class="rounded-xl border bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Butuh bantuan terkait privasi?</div>
                            <div class="text-sm text-blue-100">Tim kami siap membantu menjawab pertanyaan Anda.</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="mailto:agakomputer25@gmail.com"
                                class="inline-flex items-center rounded-md bg-white text-blue-700 px-4 py-2 font-medium">Kirim
                                Email</a>
                            <a href="https://wa.me/6281333892111" target="_blank"
                                class="inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-white">Chat
                                WA</a>
                        </div>
                    </div>
                </section>

                <p class="mt-3 text-xs text-gray-400">Catatan: Kebijakan ini adalah pedoman umum. Untuk kasus hukum khusus,
                    kami mengikuti peraturan perundang-undangan yang berlaku di Indonesia.</p>
            </article>
        </main>
    </div>
@endsection

@push('meta')
    <meta name="description"
        content="Kebijakan Privasi AGA IT COMPUTER — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pelanggan. Hubungi +62 813-3389-2111 untuk pertanyaan.">
    <meta property="og:title" content="Kebijakan Privasi | AGA IT COMPUTER">
    <meta property="og:description"
        content="Kami menghargai privasi Anda. Data digunakan untuk proses pesanan & layanan. Tidak menjual data pelanggan.">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- JSON-LD Organization (singkat) --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "AGA IT COMPUTER",
      "url": "{{ url('/') }}",
      "contactPoint": [{
        "@type": "ContactPoint",
        "telephone": "+62 813-3389-2111",
        "contactType": "customer service"
      }]
    }
    </script>
@endpush
