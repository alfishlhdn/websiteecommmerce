@extends('Auth.main')

@section('judul', 'Daftar - AGA IT COMPUTER | Toko Komputer & Service Laptop Malang')

@section('content')

<div class="w-full max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden flex flex-col md:flex-row mx-auto">
    <!-- Sisi Kiri: Logo dan Tombol Beranda -->
    <div class="w-full md:w-1/2 bg-blue-500 p-6 sm:p-10 flex flex-col items-center justify-center text-center">
        <img src="/image/agaitcomputer.png" alt="AGA IT COMPUTER"
            class="w-40 sm:w-56 md:w-72 mb-4 object-contain" />

        <!-- Tombol kembali ke beranda -->
        <a href="/"
            class="mt-4 sm:mt-6 inline-block bg-white border border-blue-600 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition text-sm font-medium">
            ⬅️ Kembali ke Beranda
        </a>
    </div>

    <!-- Sisi Kanan: Form Register -->
    <div class="w-full md:w-1/2 p-6 sm:p-10 flex flex-col justify-center">
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800">Selamat Datang di AGA IT COMPUTER 👋</h2>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Silakan daftar untuk mulai menjelajahi toko kami.</p>
        </div>

        <form class="space-y-5" action="/register" method="POST">
            @csrf
            <input type="hidden" name="role" value="user">

            <!-- Nama -->
            <div>
                <label class="text-sm font-medium text-gray-700">Nama</label>
                <input type="text" placeholder="Nama" name="name" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base" />
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telephone -->
            <div>
                <label class="text-sm font-medium text-gray-700">Telephone</label>
                <input type="tel" name="phone" placeholder="08xxxx" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base" />
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" placeholder="you@example.com" name="email" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input id="password" type="password" placeholder="••••••••" name="password" required
                        class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base" />
                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-3 text-gray-500 focus:outline-none">😖</button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkbox Syarat & Ketentuan -->
            <div class="flex items-start gap-2 text-sm">
                <input type="checkbox" required class="mt-1" />
                <span>Saya menyetujui
                    <a href="/syarat-ketentuan" class="text-blue-500 underline">syarat & ketentuan</a>
                </span>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition font-medium">
                Daftar
            </button>

            <!-- Social Login -->
            <div class="text-center text-sm text-gray-500 mt-6">Atau daftar dengan</div>
            <div class="flex justify-center space-x-4 mt-4">
                <a href="{{ url('auth/google') }}"
                    class="bg-white border px-4 py-2 rounded-lg shadow hover:bg-gray-100 flex items-center text-sm sm:text-base">
                    <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" class="w-5 h-5 mr-2" alt="Google" />
                    <span class="text-gray-700">Google</span>
                </a>
            </div>

            <!-- Sudah punya akun -->
            <div class="text-center mt-6 text-sm text-gray-600">
                Sudah punya akun?
                <a href="/Masuk" class="text-blue-600 hover:underline font-medium">Masuk</a>
            </div>
        </form>
    </div>
</div>


@endsection
