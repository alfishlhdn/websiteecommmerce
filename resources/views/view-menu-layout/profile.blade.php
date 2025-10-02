@extends('Layouts.main')

@section('judul', 'Profil | Informasi Pribadi')

@section('content')
    <div class="bg-gray-50 min-h-[80vh] py-8">
        <div class="max-w-7xl mx-auto grid grid-cols-12 gap-6 px-4">

            {{-- Sidebar --}}
            <aside class="col-span-12 md:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="p-6 border-b">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-200">
                                <img src="/image/logoagaitcomputer.png" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                    </div>

                    <nav class="p-2">
                        <ul id="profile-menu" class="space-y-1 text-sm">
                            <li>
                                <button data-target="informasi"
                                    class="menu-item flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100">
                                    <i class="fa-solid fa-id-card w-5 text-gray-500"></i> Informasi Pribadi
                                </button>
                            </li>
                            <li>
                                <button data-target="alamat"
                                    class="menu-item flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100">
                                    <i class="fa-solid fa-location-dot w-5 text-gray-500"></i> Alamat
                                </button>
                            </li>
                            <li>
                                <button data-target="pesanan"
                                    class="menu-item flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100">
                                    <i class="fa-solid fa-bag-shopping w-5 text-gray-500"></i> Pesanan Saya
                                </button>
                            </li>
                            <li>
                                <button data-target="metode"
                                    class="menu-item flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100">
                                    <i class="fa-solid fa-credit-card w-5 text-gray-500"></i> Metode Pembayaran
                                </button>
                            </li>
                            <li>
                                <button data-target="bantuan"
                                    class="menu-item flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100">
                                    <i class="fa-solid fa-circle-question w-5 text-gray-500"></i> Bantuan
                                </button>
                            </li>
                            <li>
                                <button id="sign-out"
                                    class="flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl hover:bg-gray-100 text-red-600">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Keluar
                                </button>
                            </li>
                            <!-- Pop-up Modal -->
                            <div id="logout-modal"
                                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
                                <div class="bg-white rounded-2xl shadow-lg w-full max-w-sm p-6 text-center">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Keluar</h2>
                                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari aplikasi?</p>

                                    <div class="flex justify-center gap-4">
                                        <button id="cancel-logout"
                                            class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">
                                            Batal
                                        </button>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </nav>
                </div>
            </aside>


            {{-- Main Content --}}
            <main class="col-span-12 md:col-span-9">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

                    <div id="section-header" class="p-6 border-b bg-gradient-to-b from-gray-50 to-white rounded-t-2xl">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 text-center sm:text-left">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i id="section-icon" class="fa-solid fa-id-card text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <h1 id="section-title" class="text-xl sm:text-2xl font-extrabold text-gray-800">
                                    Informasi Pribadi
                                </h1>
                                <p id="section-sub" class="text-sm text-gray-500">
                                    Kelola data akun Anda — nama, email, telepon, dan informasi lain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Informasi Pribadi (diisi dari DB) --}}
                        <section id="informasi" class="profile-section">
                            <form action="{{ route('profilupdate') }}" method="POST" class="space-y-5">
                                @csrf
                                @method('PUT')

                                @php
                                    $fullName = old('name', $user->name ?? '');
                                    $parts = preg_split('/\s+/', trim($fullName));
                                    $first = old('first_name', $parts[0] ?? '');
                                    $last = old(
                                        'last_name',
                                        isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '',
                                    );
                                @endphp

                                {{-- Nama Depan & Belakang --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Nama Depan</label>
                                        <input name="first_name" value="{{ $first }}" type="text"
                                            class="w-full h-[42px] rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Nama Depan">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Nama Belakang</label>
                                        <input name="last_name" value="{{ $last }}" type="text"
                                            class="w-full h-[42px] rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Nama Belakang (opsional)">
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                                    <input name="email" value="{{ old('email', $user->email ?? '') }}" type="email"
                                        class="w-full h-[42px] rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="email@contoh.com" required>
                                    @error('email')
                                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Telepon --}}
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="sm:w-28">
                                        <label class="block text-sm text-gray-600 mb-1">Negara</label>
                                        <div
                                            class="flex items-center gap-2 rounded-xl border border-gray-200 px-3 h-[42px] bg-gray-50">
                                            <img src="https://flagcdn.com/w20/id.png" class="w-5 h-4" alt="flag">
                                            <span class="text-sm text-gray-600">+62</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm text-gray-600 mb-1">Telepon</label>
                                        <input name="phone" value="{{ old('phone', $user->phone ?? '') }}" type="text"
                                            class="w-full h-[42px] rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="0812-xxxx-xxxx">
                                        @error('phone')
                                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password Baru --}}
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Password Baru (opsional)</label>
                                    <div class="relative">
                                        <input name="password" type="password" id="password-field"
                                            class="w-full h-[42px] rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 pr-10"
                                            placeholder="Kosongkan jika tidak ingin mengganti">
                                        @error('password')
                                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                        @enderror

                                        {{-- Tombol toggle --}}
                                        <button type="button" id="toggle-password"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <!-- eye icon -->
                                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <!-- eye-off icon -->
                                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.804-4.419M6.223 6.223A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.958 9.958 0 01-4.158 4.989M3 3l18 18" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                                    <button type="submit"
                                        class="px-5 py-2.5 w-full sm:w-auto rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                                        Simpan
                                    </button>
                                    <a href="{{ url()->current() }}"
                                        class="px-5 py-2.5 w-full sm:w-auto rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </section>

                        {{-- Alamat (diambil dari DB user_addresses) --}}
                        <section id="alamat" class="profile-section hidden">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="font-semibold text-lg">Daftar Alamat</h3>
                                    <p class="text-sm text-gray-500">Kelola alamat pengiriman Anda.</p>
                                </div>

                                {{-- Tombol tambah alamat --}}
                                <div class="flex justify-end">
                                    <button id="btn-add-address" class="px-4 py-2 rounded-xl bg-blue-600 text-white">
                                        + Tambah Alamat
                                    </button>
                                </div>

                                {{-- List alamat dari DB --}}
                                <div id="addresses-list" class="space-y-4">
                                    @foreach ($addresses as $addr)
                                        <div class="border rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3"
                                            data-id="{{ $addr->id }}">
                                            <div class="flex-1">
                                                <div class="font-semibold">{{ $addr->label }}</div>
                                                <div class="text-sm text-gray-600">{{ $addr->alamat_lengkap }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $addr->kelurahan }}, {{ $addr->kecamatan }}, {{ $addr->kota }},
                                                    {{ $addr->provinsi }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">{{ $addr->telepon }}</div>
                                            </div>

                                            <div class="flex gap-2 sm:shrink-0">
                                                <button class="btn-edit-address px-3 py-1 rounded-md bg-gray-100 text-sm"
                                                    data-address='@json($addr)'>Edit</button>
                                                <form method="POST" action="{{ route('address.destroy', $addr->id) }}"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="px-3 py-1 rounded-md bg-red-50 text-sm text-red-600 delete-btn"
                                                        data-id="{{ $addr->id }}">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($addresses->isEmpty())
                                        <div class="border rounded-xl p-4 text-sm text-gray-600">
                                            Belum ada alamat tersimpan.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Modal / Panel form tambah / edit alamat --}}
                            <div id="address-modal"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden p-4">
                                <div
                                    class="bg-white rounded-2xl w-full max-w-2xl p-4 sm:p-6 shadow-xl animate-fadeIn transform transition-all scale-95
                                            max-h-[90vh] overflow-y-auto mt-6 mb-6">
                                    <h4 id="modal-title" class="font-semibold text-base sm:text-lg mb-4">
                                        Tambah Alamat
                                    </h4>

                                    <form id="address-form" method="POST" action="{{ route('address.store') }}"
                                        class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="id" id="addr-id" value="">

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Label</label>
                                                <input name="label" id="addr-label" type="text"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3"
                                                    placeholder="Contoh: Rumah / Kantor" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Telepon</label>
                                                <input name="telepon" id="addr-telepon" type="text"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3"
                                                    placeholder="0812-xxxx-xxxx">
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm text-gray-600 mb-1">Alamat Lengkap</label>
                                                <textarea name="alamat_lengkap" id="addr-alamat" class="w-full rounded-xl border-gray-200 p-3 resize-y min-h-[90px]"
                                                    placeholder="Jalan, no, rt/rw, dsb" required></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Provinsi</label>
                                                <select id="provinsi" name="provinsi"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3" required>
                                                    <option value="">Memuat provinsi...</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Kota / Kabupaten</label>
                                                <select id="kota" name="kota"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3" required>
                                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                                </select>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm text-gray-600 mb-1">Kecamatan</label>
                                                <select id="kecamatan" name="kecamatan"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3" required>
                                                    <option value="">Pilih kota terlebih dahulu</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm text-gray-600 mb-1">Kelurahan / Desa</label>
                                                <select id="kelurahan" name="kelurahan"
                                                    class="w-full rounded-xl border-gray-200 h-[42px] px-3" required>
                                                    <option value="">Pilih kecamatan terlebih dahulu</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
                                            <button type="button" id="modal-cancel"
                                                class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 w-full sm:w-auto">
                                                Batal
                                            </button>
                                            <button type="submit" id="modal-save"
                                                class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 w-full sm:w-auto">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            {{-- Modal Konfirmasi Hapus (satu global modal) --}}
                            <div id="deleteModal"
                                class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                                <div
                                    class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 max-w-sm w-full animate-fadeIn transform transition-all scale-95">
                                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Konfirmasi</h2>
                                    <p class="text-sm sm:text-base text-gray-600 mb-6">Apakah Anda yakin ingin menghapus
                                        alamat ini?</p>
                                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                                        <button id="cancelDelete"
                                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 w-full sm:w-auto">
                                            Batal
                                        </button>
                                        <button id="confirmDelete"
                                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white w-full sm:w-auto">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </section>


                        {{-- Pesanan --}}
                        <section id="pesanan" class="profile-section">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="font-semibold text-lg">Pesanan Saya</h3>
                                    <p class="text-sm text-gray-500">Riwayat pesanan dan status pengiriman.</p>
                                </div>

                                <div class="divide-y">
                                    @foreach ($orders as $order)
                                        <div
                                            class="py-4 flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                                            {{-- Info Pesanan --}}
                                            <div>
                                                <div class="font-semibold">#{{ $order->kode_pesanan }}</div>
                                                <div class="text-sm text-gray-600">
                                                    Dipesan: {{ $order->created_at->format('d M Y') }}
                                                </div>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <div>
                                                        Status Pembayaran:
                                                        @if ($order->payment_status === 'pending')
                                                            <span class="font-medium text-red-600">Belum Dibayar</span>
                                                        @elseif($order->payment_status === 'waiting_confirmation')
                                                            <span class="font-medium text-yellow-600">Menunggu
                                                                Konfirmasi</span>
                                                        @elseif($order->payment_status === 'paid')
                                                            <span class="font-medium text-green-600">Sudah Dibayar</span>
                                                        @elseif($order->payment_status === 'cancelled')
                                                            <span class="font-medium text-gray-500">Dibatalkan</span>
                                                        @else
                                                            <span class="font-medium text-blue-600">
                                                                {{ ucfirst($order->payment_status) }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        Status Pengiriman:
                                                        @if ($order->shipping_status === 'pending')
                                                            <span class="font-medium text-gray-600">Belum Diproses</span>
                                                        @elseif($order->shipping_status === 'processing')
                                                            <span class="font-medium text-yellow-600">Sedang
                                                                Diproses</span>
                                                        @elseif($order->shipping_status === 'shipped')
                                                            <span class="font-medium text-blue-600">Dikirim</span>
                                                        @elseif($order->shipping_status === 'delivered')
                                                            <span class="font-medium text-green-600">Terkirim</span>
                                                        @elseif($order->shipping_status === 'cancelled')
                                                            <span class="font-medium text-red-600">Dibatalkan</span>
                                                        @else
                                                            <span class="font-medium text-gray-400">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tombol Aksi --}}
                                            <div class="flex flex-wrap gap-2">
                                                <button class="px-3 py-1 rounded-md bg-gray-100 text-sm detail-btn"
                                                    data-id="{{ $order->id }}">
                                                    Detail
                                                </button>

                                                {{-- Jika menunggu konfirmasi pembayaran --}}
                                                @if ($order->payment_status === 'waiting_confirmation')
                                                    <a href="https://wa.me/6281333892111?text=Halo%20AgaKomputer,%20saya%20ingin%20membatalkan%20pesanan%20{{ $order->kode_pesanan }}%20dan%20meminta%20pengembalian%20dana."
                                                        target="_blank"
                                                        class="px-3 py-1 rounded-md bg-green-500 text-white text-sm">
                                                        Hubungi Admin (Refund)
                                                    </a>

                                                    {{-- Jika sudah dibayar tapi belum dikirim (masih bisa batal) --}}
                                                @elseif ($order->payment_status === 'paid' && in_array($order->shipping_status, ['pending', 'processing']))
                                                    <a href="https://wa.me/6281333892111?text=Halo%20AgaKomputer,%20saya%20ingin%20membatalkan%20pesanan%20{{ $order->kode_pesanan }}%20dan%20meminta%20pengembalian%20dana."
                                                        target="_blank"
                                                        class="px-3 py-1 rounded-md bg-green-500 text-white text-sm">
                                                        Hubungi Admin (Refund)
                                                    </a>
                                                @endif

                                                @if (in_array($order->payment_status, ['pending']))
                                                    <button class="px-3 py-1 rounded-md bg-blue-100 text-sm pay-btn"
                                                        data-id="{{ $order->id }}"
                                                        data-kode="{{ $order->kode_pesanan }}"
                                                        data-subtotal="{{ $order->total }}"
                                                        data-method="{{ $order->payment_method_id == 1 ? 'qris' : 'other' }}"
                                                        data-qris="{{ $order->qris_payload }}"
                                                        data-image="{{ $order->qris_image_path }}"
                                                        data-code="{{ $order->paymentMethod->code }}"
                                                        data-description="{{ $order->paymentMethod->description }}">
                                                        Lanjutkan Pembayaran
                                                    </button>

                                                    <button class="px-3 py-1 rounded-md bg-red-100 text-sm cancel-btn"
                                                        data-id="{{ $order->id }}">
                                                        Batalkan
                                                    </button>
                                                @endif

                                                {{-- Tombol Pesanan Diterima --}}
                                                @if ($order->payment_status === 'paid' && $order->shipping_status === 'shipped')
                                                    <div class="flex flex-col gap-1">
                                                        <button
                                                            class="px-3 py-1 rounded-md bg-green-100 text-sm confirm-btn"
                                                            data-id="{{ $order->id }}">
                                                            Pesanan Diterima
                                                        </button>
                                                        <p class="text-xs text-gray-500">Klik tombol ini jika pesanan sudah
                                                            sampai.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination links -->
                                <div class="mt-6">
                                    {{ $orders->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </section>

                        <!-- Modal QRIS -->
                        <div id="qrisModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                            <div
                                class="bg-white rounded-xl shadow-xl w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold">Pembayaran QRIS</h3>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Kode Pesanan:</p>
                                        <p id="modalKodePesanan" class="font-semibold">-</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600">Total yang harus dibayar:</p>
                                        <p id="modalTotal" class="text-xl font-bold text-green-700">Rp0</p>
                                    </div>

                                    <div class="border rounded-lg p-3 text-center">
                                        <img id="qrisImage" src="" alt="QRIS"
                                            class="mx-auto max-h-64 object-contain">
                                        <p class="text-xs text-gray-500 mt-2">Scan QRIS ini menggunakan aplikasi
                                            e-wallet/banking Anda.</p>
                                    </div>

                                    <div class="mt-2">
                                        <label class="block font-semibold mb-1">Upload bukti pembayaran (jpg/png)</label>
                                        <input type="file" id="proofFile" accept="image/*"
                                            class="w-full border rounded p-2">
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                                        <button id="btnSubmitProof"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg w-full sm:w-auto">Kirim
                                            Bukti</button>
                                        <button id="btnCloseQris"
                                            class="px-4 py-2 bg-gray-200 rounded-lg w-full sm:w-auto">Tutup</button>
                                    </div>

                                    <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mt-2">
                                        Setelah mengirim bukti, status akan menjadi <b>menunggu konfirmasi</b>. Silakan cek
                                        Profil Anda.
                                    </p>
                                </div>
                            </div>
                        </div>


                        <!-- Modal Non-QRIS (Bank / E-Wallet) -->
                        <div id="otherPaymentModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                            <div
                                class="bg-white rounded-xl shadow-xl w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold">Pembayaran Transfer / E-Wallet</h3>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Kode Pesanan:</p>
                                        <p id="otherModalKodePesanan" class="font-semibold">-</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600">Total yang harus dibayar:</p>
                                        <p id="otherModalTotal" class="text-xl font-bold text-green-700">Rp0</p>
                                    </div>

                                    <div class="border rounded-lg p-3 text-center">
                                        <p class="text-sm text-gray-600">Nomor Tujuan Pembayaran:</p>
                                        <p id="paymentNumber" class="text-2xl font-bold text-blue-700 break-all">-</p>
                                        <p id="paymentInstructions" class="text-xs text-gray-500 mt-1">Gunakan nomor ini
                                            untuk transfer.</p>
                                    </div>

                                    <div class="mt-2">
                                        <label class="block font-semibold mb-1">Upload bukti pembayaran (jpg/png)</label>
                                        <input type="file" id="otherProofFile" accept="image/*"
                                            class="w-full border rounded p-2">
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                                        <button id="btnSubmitOtherProof"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg w-full sm:w-auto">
                                            Kirim Bukti
                                        </button>
                                        <button id="btnCloseOther"
                                            class="px-4 py-2 bg-gray-200 rounded-lg w-full sm:w-auto">
                                            Tutup
                                        </button>
                                    </div>

                                    <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mt-2">
                                        Setelah mengirim bukti, status akan menjadi <b>menunggu konfirmasi</b>. Silakan cek
                                        Profil Anda.
                                    </p>
                                </div>
                            </div>
                        </div>



                        <!-- Modal Batalkan -->
                        <div id="cancelOrderModal"
                            class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-4 sm:p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-lg sm:text-xl font-bold text-red-600">Batalkan Pesanan</h3>
                                    <button id="btnCloseCancel"
                                        class="text-gray-500 hover:underline text-sm sm:text-base">Tutup</button>
                                </div>
                                <p class="text-gray-700 mb-4 text-sm sm:text-base">
                                    Apakah kamu yakin ingin membatalkan pesanan ini?
                                </p>
                                <div class="flex flex-col sm:flex-row justify-end gap-2">
                                    <button id="btnCancelNo"
                                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 text-sm sm:text-base">
                                        Tidak
                                    </button>
                                    <button id="btnCancelYes"
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm sm:text-base">
                                        Ya, Batalkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail -->
                        <div id="orderDetailModal"
                            class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                            <div
                                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-4 sm:p-6 relative animate-fadeIn transform transition-all scale-95">

                                <!-- Close button -->
                                <button id="btnCloseDetail"
                                    class="absolute top-3 right-3 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-full w-8 h-8 flex items-center justify-center shadow">
                                    ✕
                                </button>

                                <!-- Title -->
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 border-b pb-2">Detail Pesanan
                                </h3>

                                <!-- Content -->
                                <div id="orderDetailContent" class="space-y-4 text-sm sm:text-base text-gray-700">
                                    <p class="text-gray-500">Memuat...</p>
                                </div>
                            </div>
                        </div>



                        {{-- Metode Pembayaran --}}
                        <section id="metode" class="profile-section hidden">
                            <div class="space-y-5">
                                <!-- Header -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <h3 class="font-semibold text-lg sm:text-xl">Metode Pembayaran</h3>
                                        <p class="text-sm sm:text-base text-gray-500 mt-1">
                                            Pembayaran di toko ini dapat dilakukan melalui
                                            <span class="font-medium">QRIS</span> atau
                                            <span class="font-medium">Transfer Bank</span>.
                                            Untuk detail tata cara pembayaran silakan cek di halaman checkout.
                                        </p>
                                    </div>
                                </div>

                                <!-- Cara bayar -->
                                <div class="rounded-2xl border p-3 sm:p-4 bg-gray-50">
                                    <div class="flex items-start gap-3 sm:gap-4">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white border flex items-center justify-center">
                                            <i class="fa-solid fa-money-check-dollar text-lg sm:text-xl text-gray-700"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-base sm:text-lg">Cara bayar</h4>
                                            <ol
                                                class="list-decimal ml-5 text-sm sm:text-base text-gray-700 space-y-1 mt-1">
                                                <li>Lakukan pemesanan seperti biasa.</li>
                                                <li>Pada halaman checkout, sistem akan menampilkan pilihan pembayaran:
                                                    <span class="font-medium">QRIS dinamis</span> atau
                                                    <span class="font-medium">Transfer Bank</span>.
                                                </li>
                                                <li>Pilih metode yang Anda inginkan dan selesaikan pembayaran sesuai
                                                    instruksi.</li>
                                            </ol>
                                            <p class="text-xs sm:text-sm text-gray-500 mt-3">
                                                Instruksi detail pembayaran hanya akan muncul di halaman checkout & berlaku
                                                sesuai waktu kedaluwarsa.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keamanan & Dukungan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="rounded-2xl border p-3 sm:p-4">
                                        <h5 class="font-semibold mb-1 text-base sm:text-lg">Keamanan</h5>
                                        <p class="text-sm sm:text-base text-gray-600">
                                            Transaksi diproses oleh penyelenggara payment gateway/PSP yang berizin Bank
                                            Indonesia.
                                            Status pembayaran akan diperbarui otomatis setelah kami menerima
                                            <em>pesanan & bukti pembayaran yang valid</em>.
                                        </p>
                                    </div>
                                    <div class="rounded-2xl border p-3 sm:p-4">
                                        <h5 class="font-semibold mb-1 text-base sm:text-lg">Dukungan</h5>
                                        <p class="text-sm sm:text-base text-gray-600">
                                            Bisa dibayar dari hampir semua aplikasi bank & e-wallet yang mendukung QRIS
                                            (BRI, BCA, BNI, Mandiri, Jago, OVO, DANA, ShopeePay, dsb),
                                            atau langsung melalui Transfer Bank yang tersedia di halaman checkout.
                                        </p>
                                    </div>
                                </div>

                                <!-- FAQ -->
                                <div class="rounded-2xl border p-3 sm:p-4">
                                    <h5 class="font-semibold mb-2 text-base sm:text-lg">FAQ singkat</h5>
                                    <ul class="text-sm sm:text-base text-gray-700 space-y-2">
                                        <li>
                                            <span class="font-medium">Apakah bisa metode lain?</span><br>
                                            Saat ini mendukung <span class="font-medium">QRIS</span> dan
                                            <span class="font-medium">Transfer Bank</span>.
                                            Jika diperlukan, metode lain dapat ditambahkan di kemudian hari.
                                        </li>
                                        <li>
                                            <span class="font-medium">Di mana melihat detail pembayaran?</span><br>
                                            Semua instruksi lengkap hanya muncul di halaman checkout setelah membuat
                                            pesanan.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </section>


                        {{-- Bantuan (diperbarui: profesional + FAQ + kontak cepat) --}}
                        <section id="bantuan" class="profile-section hidden">
                            <div class="space-y-6">
                                <!-- Header -->
                                <div>
                                    <h3 class="font-semibold text-lg md:text-xl">Bantuan & Dukungan</h3>
                                    <p class="text-sm md:text-base text-gray-500">
                                        Punya pertanyaan? Di sini jawaban cepat & cara menghubungi tim kami.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- FAQ -->
                                    <div class="space-y-4">
                                        <div class="rounded-2xl border p-4 md:p-6">
                                            <h4 class="font-semibold mb-3 md:mb-4 text-base md:text-lg">Pertanyaan Umum
                                                (FAQ)</h4>

                                            <div id="faq-list" class="space-y-3">
                                                <!-- Item FAQ -->
                                                <div class="faq-item border rounded-lg">
                                                    <button
                                                        class="faq-q w-full text-left px-4 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                                                        <span class="font-medium text-sm md:text-base">Bagaimana cara
                                                            melacak pesanan saya?</span>
                                                        <span class="faq-toggle text-sm text-gray-500">+</span>
                                                    </button>
                                                    <div class="faq-a px-4 pb-3 hidden text-sm text-gray-600">
                                                        Masuk ke <a href="" class="text-blue-600 underline">Halaman
                                                            Pesanan</a>, klik
                                                        "Detail Pada Profil diMenu Pesanan Saya" pada nomor pesanan yang ingin dilacak. Jika membutuhkan
                                                        bantuan, hubungi CS.
                                                    </div>
                                                </div>

                                                <!-- Item FAQ -->
                                                <div class="faq-item border rounded-lg">
                                                    <button
                                                        class="faq-q w-full text-left px-4 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                                                        <span class="font-medium text-sm md:text-base">Berapa lama
                                                            pengiriman biasanya?</span>
                                                        <span class="faq-toggle text-sm text-gray-500">+</span>
                                                    </button>
                                                    <div class="faq-a px-4 pb-3 hidden text-sm text-gray-600">
                                                        Estimasi pengiriman 1–5 hari kerja tergantung lokasi dan jenis
                                                        pengiriman. Untuk informasi detail cek pada halaman checkout sebelum
                                                        konfirmasi atau juga bisa cek diprofil klik detail pada profil dimenu pesanan saya jika sudah bayar atau barang sudah dikirim .
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick links -->
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <a href="/syarat-ketentuan"
                                                class="flex-1 rounded-2xl border p-3 text-center hover:bg-gray-50 text-sm md:text-base transition">
                                                Syarat & Ketentuan
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Kontak -->
                                    <div class="space-y-4">
                                        <div class="rounded-2xl border p-4 md:p-6">
                                            <h4 class="font-semibold text-base md:text-lg">Hubungi Kami</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Tim support siap membantu jika masih ada yang kurang jelas.
                                            </p>

                                            <div class="mt-5 space-y-4">
                                                <!-- Email -->
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                                        <i class="fa-solid fa-envelope text-blue-600"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="text-sm font-medium">Email</div>
                                                        <div class="text-sm text-gray-600 break-words">
                                                            supportagakomputer@gmail.com</div>
                                                        <div class="mt-2">
                                                            <button
                                                                class="copy-contact px-3 py-1 text-xs md:text-sm rounded-md bg-gray-100 hover:bg-gray-200 transition"
                                                                data-copy="supportagakomputer@gmail.com">Salin
                                                                Email</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- WhatsApp -->
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                                        <i class="fa-brands fa-whatsapp text-green-600"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="text-sm font-medium">WhatsApp</div>
                                                        <div class="text-sm text-gray-600 break-words">+62 831-4332-9728
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="https://wa.me/6283143329728" target="_blank"
                                                                class="px-3 py-1 rounded-md bg-green-50 text-green-700 text-xs md:text-sm hover:bg-green-100 transition">
                                                                Chat Sekarang
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Jam kerja -->
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-1">
                                                        <div class="text-sm text-gray-600">
                                                            Jam Operasional: <span class="font-medium">Senin–Sabtu,
                                                                08:00–17:00</span>
                                                        </div>
                                                        <div class="text-xs md:text-sm text-gray-400 mt-2">
                                                            Respon hanya diberikan pada jam operasional. Pesan di luar jam
                                                            kerja atau pada hari libur akan dibalas pada jam operasional
                                                            berikutnya.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tips -->
                                        <div class="rounded-2xl border p-4 md:p-6 bg-yellow-50">
                                            <h5 class="font-semibold mb-2 text-base md:text-lg">Tips Aman</h5>
                                            <ul class="text-sm text-gray-700 list-disc ml-5 space-y-1">
                                                <li>Jangan bagikan OTP, password, atau bukti transfer ke pihak lain selain
                                                    tim resmi kami.</li>
                                                <li>Periksa selalu nomor rekening & QRIS di halaman checkout sebelum
                                                    membayar.</li>
                                                <li>Simpan bukti pembayaran jika terjadi masalah — kami akan membantumu
                                                    cepat.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div> <!-- p-6 -->
                </div> <!-- card -->
            </main>

        </div>
    </div>


    <script>
        const passwordField = document.getElementById('password-field');
        const toggleBtn = document.getElementById('toggle-password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', !isPassword);
            eyeClosed.classList.toggle('hidden', isPassword);
        });
    </script>

    {{-- JS kecil untuk FAQ accordion + search + copy --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // accordion
            document.querySelectorAll('.faq-q').forEach(btn => {
                btn.addEventListener('click', () => {
                    const item = btn.closest('.faq-item');
                    const content = item.querySelector('.faq-a');
                    const toggle = item.querySelector('.faq-toggle');
                    const isOpen = !content.classList.contains('hidden');
                    // close all
                    document.querySelectorAll('.faq-a').forEach(a => a.classList.add('hidden'));
                    document.querySelectorAll('.faq-toggle').forEach(t => t.textContent = '+');
                    // toggle this
                    if (!isOpen) {
                        content.classList.remove('hidden');
                        toggle.textContent = '−';
                    } else {
                        content.classList.add('hidden');
                        toggle.textContent = '+';
                    }
                });
            });

            // search FAQ
            const faqSearch = document.getElementById('faq-search');
            const faqList = document.getElementById('faq-list');
            const faqClear = document.getElementById('faq-clear');

            function filterFaq(q) {
                q = (q || '').toLowerCase().trim();
                faqList.querySelectorAll('.faq-item').forEach(item => {
                    const qText = item.querySelector('.faq-q').innerText.toLowerCase();
                    const aText = item.querySelector('.faq-a').innerText.toLowerCase();
                    const matched = q === '' || qText.includes(q) || aText.includes(q);
                    item.style.display = matched ? '' : 'none';
                });
            }

            if (faqSearch) {
                faqSearch.addEventListener('input', (e) => filterFaq(e.target.value));
            }
            if (faqClear) {
                faqClear.addEventListener('click', () => {
                    faqSearch.value = '';
                    filterFaq('');
                });
            }

            // copy contact (email)
            document.querySelectorAll('.copy-contact').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const value = btn.dataset.copy;
                    try {
                        await navigator.clipboard.writeText(value);
                        btn.textContent = 'Tersalin ✓';
                        setTimeout(() => btn.textContent = 'Salin Email', 1800);
                    } catch (err) {
                        alert('Salin gagal — silakan salin manual: ' + value);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuItems = document.querySelectorAll('#profile-menu .menu-item');
            const sections = document.querySelectorAll('.profile-section');
            const title = document.getElementById('section-title');
            const sub = document.getElementById('section-sub');
            const icon = document.getElementById('section-icon');

            const meta = {
                'informasi': {
                    title: 'Informasi Pribadi',
                    sub: 'Kelola data akun Anda — nama, email, telepon, dan informasi lain.',
                    icon: 'fa-solid fa-id-card'
                },
                'alamat': {
                    title: 'Alamat',
                    sub: 'Kelola alamat pengiriman Anda.',
                    icon: 'fa-solid fa-location-dot'
                },
                'pesanan': {
                    title: 'Pesanan Saya',
                    sub: 'Riwayat pesanan dan status pengiriman.',
                    icon: 'fa-solid fa-bag-shopping'
                },
                'metode': {
                    title: 'Metode Pembayaran',
                    sub: 'Pembayaran default: QRIS. QR akan muncul saat checkout.',
                    icon: 'fa-solid fa-credit-card'
                },

                'bantuan': {
                    title: 'Bantuan',
                    sub: 'Pertanyaan umum dan cara menghubungi kami.',
                    icon: 'fa-solid fa-circle-question'
                },
            };

            function showSection(id) {
                sections.forEach(s => s.id === id ? s.classList.remove('hidden') : s.classList.add('hidden'));
                // update header
                const m = meta[id] || meta['informasi'];
                title.textContent = m.title;
                sub.textContent = m.sub;
                icon.className = m.icon + ' text-xl text-blue-600';
                // active style di sidebar
                menuItems.forEach(btn => {
                    if (btn.dataset.target === id) {
                        btn.classList.add('bg-blue-50', 'text-blue-600');
                    } else {
                        btn.classList.remove('bg-blue-50', 'text-blue-600');
                    }
                });
            }

            // attach click
            menuItems.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.dataset.target;
                    showSection(target);
                });
            });

            const signOutBtn = document.getElementById('sign-out');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogout = document.getElementById('cancel-logout');

            // buka modal
            signOutBtn.addEventListener('click', () => {
                logoutModal.classList.remove('hidden');
            });

            // tutup modal
            cancelLogout.addEventListener('click', () => {
                logoutModal.classList.add('hidden');
            });

            // tampilkan default
            showSection('informasi');
        });
    </script>

    {{-- SCRIPT: load API wilayah + modal handling (nilai option = nama, bukan id) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnAdd = document.getElementById('btn-add-address');
            const modal = document.getElementById('address-modal');
            const form = document.getElementById('address-form');
            const cancel = document.getElementById('modal-cancel');
            const title = document.getElementById('modal-title');

            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');

            // endpoints (emsifa)
            const BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            const PROVINCES = `${BASE}/provinces.json`;

            // global prefill
            window._prefill_provinsi = null;
            window._prefill_kota = null;
            window._prefill_kecamatan = null;
            window._prefill_kelurahan = null;

            async function fetchJson(url) {
                const res = await fetch(url);
                if (!res.ok) throw new Error('Gagal memuat data wilayah: ' + res.status);
                return res.json();
            }

            // load provinces
            async function loadProvinces(selectedIdOrName = null) {
                if (!provinsiSelect) return;
                provinsiSelect.innerHTML = '<option value="">Memuat provinsi...</option>';
                try {
                    const list = await fetchJson(PROVINCES);
                    provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                    list.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.name;
                        opt.dataset.id = p.id;
                        opt.textContent = p.name;
                        if (selectedIdOrName && (selectedIdOrName == p.name || selectedIdOrName == p
                                .id)) {
                            opt.selected = true;
                        }
                        provinsiSelect.appendChild(opt);
                    });
                    const selVal = provinsiSelect.value;
                    if (selVal) {
                        const provId = provinsiSelect.selectedOptions[0]?.dataset?.id ?? null;
                        if (provId) await loadRegencies(provId, window._prefill_kota);
                    }
                } catch (err) {
                    // console.error(err);
                    provinsiSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
                }
            }

            // load regencies
            async function loadRegencies(provId, selectedIdOrName = null) {
                if (!kotaSelect) return;
                kotaSelect.innerHTML = '<option value="">Memuat kota/kab...</option>';
                if (kecamatanSelect) kecamatanSelect.innerHTML =
                    '<option value="">Pilih kota terlebih dahulu</option>';
                try {
                    const url = `${BASE}/regencies/${provId}.json`;
                    const list = await fetchJson(url);
                    kotaSelect.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                    list.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.name;
                        opt.dataset.id = k.id;
                        opt.textContent = k.name;
                        if (selectedIdOrName && (selectedIdOrName == k.name || selectedIdOrName == k
                                .id)) {
                            opt.selected = true;
                        }
                        kotaSelect.appendChild(opt);
                    });
                    const selVal = kotaSelect.value;
                    if (selVal) {
                        const kotaId = kotaSelect.selectedOptions[0]?.dataset?.id ?? null;
                        if (kotaId) await loadDistricts(kotaId, window._prefill_kecamatan);
                    }
                } catch (err) {
                    // console.error(err);
                    kotaSelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                }
            }

            // load districts
            async function loadDistricts(kotaId, selectedIdOrName = null) {
                if (!kecamatanSelect) return;
                kecamatanSelect.innerHTML = '<option value="">Memuat kecamatan...</option>';
                try {
                    const url = `${BASE}/districts/${kotaId}.json`;
                    const list = await fetchJson(url);
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    list.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.name;
                        opt.dataset.id = d.id;
                        opt.textContent = d.name;
                        if (selectedIdOrName && (selectedIdOrName == d.name || selectedIdOrName == d
                                .id)) {
                            opt.selected = true;
                        }
                        kecamatanSelect.appendChild(opt);
                    });
                    const selVal = kecamatanSelect.value;
                    if (selVal) {
                        const kecId = kecamatanSelect.selectedOptions[0]?.dataset?.id ?? null;
                        if (kecId) await loadVillages(kecId, window._prefill_kelurahan);
                    }
                } catch (err) {
                    // console.error(err);
                    kecamatanSelect.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
                }
            }

            // load villages
            async function loadVillages(kecId, selectedIdOrName = null) {
                if (!kelurahanSelect) return;
                kelurahanSelect.innerHTML = '<option value="">Memuat kelurahan...</option>';
                try {
                    const url = `${BASE}/villages/${kecId}.json`;
                    const list = await fetchJson(url);
                    kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan / Desa --</option>';
                    list.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.name;
                        opt.dataset.id = v.id;
                        opt.textContent = v.name;
                        if (selectedIdOrName && (selectedIdOrName == v.name || selectedIdOrName == v
                                .id)) {
                            opt.selected = true;
                        }
                        kelurahanSelect.appendChild(opt);
                    });
                } catch (err) {
                    // console.error(err);
                    kelurahanSelect.innerHTML = '<option value="">Gagal memuat kelurahan</option>';
                }
            }

            // binding dropdown
            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function() {
                    const provId = this.selectedOptions[0]?.dataset?.id ?? null;
                    if (!provId) {
                        if (kotaSelect) kotaSelect.innerHTML =
                            '<option value="">Pilih Kota/Kabupaten</option>';
                        if (kecamatanSelect) kecamatanSelect.innerHTML =
                            '<option value="">Pilih Kecamatan</option>';
                        if (kelurahanSelect) kelurahanSelect.innerHTML =
                            '<option value="">Pilih Kelurahan</option>';
                        return;
                    }
                    loadRegencies(provId);
                });
            }

            if (kotaSelect) {
                kotaSelect.addEventListener('change', function() {
                    const kotaId = this.selectedOptions[0]?.dataset?.id ?? null;
                    if (!kotaId) {
                        if (kecamatanSelect) kecamatanSelect.innerHTML =
                            '<option value="">Pilih Kecamatan</option>';
                        return;
                    }
                    loadDistricts(kotaId);
                });
            }

            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('change', function() {
                    const kecId = this.selectedOptions[0]?.dataset?.id ?? null;
                    if (!kecId) {
                        if (kelurahanSelect) kelurahanSelect.innerHTML =
                            '<option value="">Pilih Kelurahan</option>';
                        return;
                    }
                    loadVillages(kecId);
                });
            }

            // tambah alamat
            if (btnAdd) {
                btnAdd.addEventListener('click', () => {
                    title.textContent = 'Tambah Alamat';
                    form.action = "{{ route('address.store') }}";
                    const existingMethod = form.querySelector('input[name="_method"]');
                    if (existingMethod) existingMethod.remove();
                    form.reset();
                    document.getElementById('addr-id').value = '';
                    window._prefill_provinsi = null;
                    window._prefill_kota = null;
                    window._prefill_kecamatan = null;
                    window._prefill_kelurahan = null;
                    loadProvinces();
                    modal.classList.remove('hidden');
                });
            }

            // edit alamat
            document.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.btn-edit-address');
                if (!editBtn) return;
                const raw = editBtn.getAttribute('data-address');
                if (!raw) return;
                let data;
                try {
                    data = JSON.parse(raw);
                } catch (err) {
                    // console.error('Data address invalid JSON', err);
                    return;
                }

                title.textContent = 'Edit Alamat';
                form.action = "{{ url('/address') }}/" + data.id;

                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PUT';

                // isi field teks
                document.getElementById('addr-id').value = data.id ?? '';
                document.getElementById('addr-label').value = data.label ?? '';
                document.getElementById('addr-telepon').value = data.telepon ?? '';
                document.getElementById('addr-alamat').value = data.alamat_lengkap ?? '';

                // prefill dropdown
                window._prefill_provinsi = data.provinsi ?? data.provinsi_id ?? null;
                window._prefill_kota = data.kota ?? data.kota_id ?? null;
                window._prefill_kecamatan = data.kecamatan ?? data.kecamatan_id ?? null;
                window._prefill_kelurahan = data.kelurahan ?? data.kelurahan_id ?? null;

                loadProvinces(window._prefill_provinsi);

                modal.classList.remove('hidden');
            });

            // close modal
            if (cancel) cancel.addEventListener('click', () => modal.classList.add('hidden'));
            if (modal) modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // inisialisasi
            loadProvinces(window._prefill_provinsi ?? null);
        });

        // Hapus alamat
        let deleteForm = null;
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteForm = this.closest('form');
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            deleteForm = null;
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteForm) deleteForm.submit();
        });
    </script>

    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 sm:p-8 text-center animate-fadeIn transform transition-all scale-95">
            <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 mb-6">
                Yakin pesanan sudah diterima?
            </h3>
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <button id="confirmYesBtn"
                    class="px-4 py-2 sm:px-6 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition w-full sm:w-auto">
                    Ya
                </button>
                <button id="confirmNoBtn"
                    class="px-4 py-2 sm:px-6 sm:py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition w-full sm:w-auto">
                    Tidak
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Alert (hasil) -->
    <div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 sm:p-8 text-center animate-fadeIn transform transition-all scale-95">
            <h3 id="alertMessage" class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 mb-6">
            </h3>
            <button id="alertOkBtn"
                class="px-4 py-2 sm:px-6 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition w-full sm:w-auto">
                OK
            </button>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cancelOrderId = null; // simpan ID order yang mau dibatalkan

            // Konfirmasi pesanan diterima
            document.querySelectorAll('.confirm-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    let confirmModal = document.getElementById('confirmModal');
                    let alertModal = document.getElementById('alertModal');
                    let alertMessage = document.getElementById('alertMessage');

                    // buka modal konfirmasi
                    confirmModal.classList.remove('hidden');

                    // tombol Tidak
                    document.getElementById('confirmNoBtn').onclick = function() {
                        confirmModal.classList.add('hidden');
                    };

                    // tombol Ya
                    document.getElementById('confirmYesBtn').onclick = function() {
                        confirmModal.classList.add('hidden'); // tutup modal konfirmasi

                        fetch(`/orders/confirm`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    order_id: id
                                })
                            })
                            .then(res => res.json())
                            .then(json => {
                                // tampilkan modal hasil
                                alertMessage.textContent = json.message;
                                alertModal.classList.remove('hidden');

                                document.getElementById('alertOkBtn').onclick = function() {
                                    alertModal.classList.add('hidden');
                                    if (json.success) {
                                        location.reload();
                                    }
                                };
                            })
                            .catch(() => {
                                alertMessage.textContent = "Terjadi kesalahan, coba lagi!";
                                alertModal.classList.remove('hidden');

                                document.getElementById('alertOkBtn').onclick = function() {
                                    alertModal.classList.add('hidden');
                                };
                            });
                    };
                });
            });

            // buka detail modal
            document.querySelectorAll('.detail-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;

                    fetch(`/orders/${id}/detail`)
                        .then(res => res.json())
                        .then(json => {
                            // console.log("DEBUG JSON:", json); // 👈 cek isi response

                            if (json.success) {
                                let o = json.order;

                                // link resi universal
                                let trackingLink = '';
                                if (
                                    o.nomor_resi &&
                                    o.payment_status === 'paid' &&
                                    (o.shipping_status === 'shipped' || o.shipping_status ===
                                        'delivered')
                                ) {
                                    let resi = encodeURIComponent(o.nomor_resi);
                                    trackingLink = `
                            <div class="mt-3 p-3 bg-gray-50 border rounded-lg">
                                <p><b>Nomor Resi:</b> ${o.nomor_resi}</p>
                                <p><b>Ekspedisi:</b> ${o.kurir && o.kurir.name ? o.kurir.name.toUpperCase() : '-'}</p>
                                <a href="https://cekresi.com/?noresi=${resi}" target="_blank"
                                   class="text-blue-600 underline">Lacak Resi</a>
                            </div>`;
                                }

                                let itemsList = o.items.map((i, idx) =>
                                    `<li>${idx + 1}. ${i.nama_produk} x${i.jumlah} - Rp${i.subtotal}</li>`
                                ).join('');

                                let shippingBadge = '';
                                if (o.shipping_status) {
                                    if (o.shipping_status === 'delivered') {
                                        shippingBadge =
                                            `<span class="px-2 py-1 rounded text-sm bg-green-100 text-green-700">${o.shipping_status}</span>`;
                                    } else if (o.shipping_status === 'shipped') {
                                        shippingBadge =
                                            `<span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-700">${o.shipping_status}</span>`;
                                    } else {
                                        shippingBadge =
                                            `<span class="px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-700">${o.shipping_status}</span>`;
                                    }
                                } else {
                                    shippingBadge =
                                        `<span class="px-2 py-1 rounded text-sm bg-gray-100 text-gray-600">-</span>`;
                                }

                                let html = `
                        <div class="grid grid-cols-2 gap-4">
                            <p><b>Kode Pesanan:</b> ${o.kode_pesanan}</p>
                            <p><b>Total:</b> Rp${o.total}</p>
                            <p><b>Status Pembayaran:</b>
                                <span class="px-2 py-1 rounded text-sm ${o.payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                                    ${o.payment_status}
                                </span>
                            </p>
                            <p><b>Status Pengiriman:</b> ${shippingBadge}</p>
                        </div>

                        <hr class="my-3">

                        <h4 class="font-semibold">Item Pesanan:</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            ${itemsList}
                        </ul>

                        ${trackingLink}
                    `;

                                document.getElementById('orderDetailContent').innerHTML = html;
                                document.getElementById('orderDetailModal').classList.remove(
                                    'hidden');
                            } else {
                                alert(json.message || "Terjadi kesalahan.");
                            }
                        })
                        .catch(err => {
                            // console.error("Fetch Error:", err); // 👈 cek error fetch
                        });
                });
            });

            // tutup modal
            document.getElementById('btnCloseDetail').addEventListener('click', function() {
                document.getElementById('orderDetailModal').classList.add('hidden');
            });

            // buka modal batalkan
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    cancelOrderId = this.dataset.id;
                    document.getElementById('cancelOrderModal').classList.remove('hidden');
                });
            });

            // tombol tutup modal
            document.getElementById('btnCloseCancel').addEventListener('click', function() {
                document.getElementById('cancelOrderModal').classList.add('hidden');
                cancelOrderId = null;
            });
            document.getElementById('btnCancelNo').addEventListener('click', function() {
                document.getElementById('cancelOrderModal').classList.add('hidden');
                cancelOrderId = null;
            });

            // tombol "Ya, Batalkan" → request ke server
            document.getElementById('btnCancelYes').addEventListener('click', function() {
                if (!cancelOrderId) return;

                fetch(`/orders/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: cancelOrderId
                        })
                    })
                    .then(res => res.json())
                    .then(json => {
                        // tampilkan modal hasil
                        alertMessage.textContent = json.message;
                        alertModal.classList.remove('hidden');

                        document.getElementById('alertOkBtn').onclick = function() {
                            alertModal.classList.add('hidden');
                            if (json.success) {
                                location.reload();
                            }
                        };
                    })
                    .catch(() => {
                        alertMessage.textContent = "Terjadi kesalahan, coba lagi!";
                        alertModal.classList.remove('hidden');

                        document.getElementById('alertOkBtn').onclick = function() {
                            alertModal.classList.add('hidden');
                        };
                    });

                // tutup modal
                document.getElementById('cancelOrderModal').classList.add('hidden');
                cancelOrderId = null;
            });
        });
    </script>

    <script>
        let currentOrderId = null;
        let alertMessage = document.getElementById('alertMessage');

        document.querySelectorAll('.pay-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                currentOrderId = btn.dataset.id;
                let kode = btn.dataset.kode;
                let total = btn.dataset.subtotal;
                let method = btn.dataset.method;
                let items = JSON.parse(btn.dataset.items || "[]");

                // daftar item di modal
                let itemsList = items.map((i, idx) =>
                    `<li>${idx + 1}. ${i.nama_produk} x${i.jumlah} - Rp${parseInt(i.subtotal).toLocaleString('id-ID')}</li>`
                ).join('');

                if (method === 'qris') {
                    document.getElementById('modalKodePesanan').innerText = kode;
                    document.getElementById('modalTotal').innerText = "Rp" + parseInt(total).toLocaleString(
                        'id-ID');

                    // ambil QRIS dari DB
                    let qrisImage = btn.dataset.image ? `/storage/${btn.dataset.image}` :
                        "/images/qris-default.png";
                    document.getElementById('qrisImage').src = qrisImage;

                    document.getElementById('qrisModal').classList.remove('hidden');
                } else {
                    document.getElementById('otherModalKodePesanan').innerText = kode;
                    document.getElementById('otherModalTotal').innerText = "Rp" + parseInt(total)
                        .toLocaleString('id-ID');

                    // misal rekening di DB (sementara masih statis)
                    let codonumber = btn.dataset.code;
                    document.getElementById('paymentNumber').innerText = codonumber;
                    let namenumber = btn.dataset.description;
                    document.getElementById('paymentInstructions').innerText =
                        namenumber;
                    document.getElementById('otherPaymentModal').classList.remove('hidden');
                }
            });
        });


        // Close modal
        function closeQrisModal() {
            document.getElementById('qrisModal').classList.add('hidden');
        }

        function closeOtherPaymentModal() {
            document.getElementById('otherPaymentModal').classList.add('hidden');
        }
        document.getElementById('btnCloseQris').addEventListener('click', closeQrisModal);
        document.getElementById('btnCloseOther').addEventListener('click', closeOtherPaymentModal);

        // Submit proof QRIS
        document.getElementById('btnSubmitProof').addEventListener('click', async () => {
            let file = document.getElementById('proofFile').files[0];
            if (!file) {
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
                return;
            }
            let formData = new FormData();
            formData.append('order_id', currentOrderId);
            formData.append('proof', file);

            let res = await fetch("{{ route('checkout.upload_proof') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            let json = await res.json();
            if (json.success) {
                ;
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
                document.getElementById('alertOkBtn').onclick = function() {
                    alertModal.classList.add('hidden');
                    if (json.success) {
                        location.reload();
                    }
                };
            } else {
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
            }
        });

        // Submit proof Non-QRIS
        document.getElementById('btnSubmitOtherProof').addEventListener('click', async () => {
            let file = document.getElementById('otherProofFile').files[0];
            if (!file) {
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
                return;
            }
            let formData = new FormData();
            formData.append('order_id', currentOrderId);
            formData.append('proof', file);

            let res = await fetch("{{ route('checkout.upload_proof') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            let json = await res.json();
            if (json.success) {
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
                document.getElementById('alertOkBtn').onclick = function() {
                    alertModal.classList.add('hidden');
                    if (json.success) {
                        location.reload();
                    }
                };
            } else {
                alertMessage.textContent = json.message;
                alertModal.classList.remove('hidden');
            }
        });
    </script>




@endsection
