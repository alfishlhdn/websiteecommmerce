@extends('Auth.main')

@section('judul', 'Masuk - AGA IT COMPUTER | Toko Komputer & Service Laptop Malang')

@section('content')

 <div class="w-full max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden flex flex-col md:flex-row mx-auto">
    <!-- Sisi Kiri: Logo dan Tombol Beranda -->
    <div class="w-full md:w-1/2 bg-blue-500 p-6 sm:p-10 flex flex-col items-center justify-center text-center">
        <img src="/image/agaitcomputer.png" alt="AGA IT COMPUTER"
            class="w-48 sm:w-64 md:w-80 mb-4 object-contain" />

        <!-- Tombol kembali ke beranda -->
        <a href="/"
            class="mt-4 sm:mt-6 inline-block bg-white border border-blue-600 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition text-sm font-medium">
            ⬅️ Kembali ke Beranda
        </a>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="w-full md:w-1/2 p-6 sm:p-10 flex flex-col justify-center">
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800">
                Selamat Datang di AGA IT COMPUTER 👋
            </h2>
            <p class="text-sm sm:text-base text-gray-500 mt-1">
                Silakan masuk untuk mulai menjelajahi toko kami.
            </p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 🔔 Notification Toast -->
        <div id="notification-toast" class="fixed top-5 right-5 z-50 space-y-3">
            @if (session('success'))
                <div
                    class="bg-green-500 text-white px-4 py-3 rounded shadow-lg flex items-center justify-between min-w-[250px] animate-slide-in">
                    <span class="text-sm">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-xl leading-none">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-500 text-white px-4 py-3 rounded shadow-lg flex items-center justify-between min-w-[250px] animate-slide-in">
                    <span class="text-sm">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-xl leading-none">&times;</button>
                </div>
            @endif
        </div>

        <!-- Form Login -->
        <form class="space-y-5" action="/login" method="POST">
            @csrf
            <!-- Email -->
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" placeholder="you@example.com" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base" />
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input id="password" type="password" placeholder="••••••••" name="password"
                        class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base" />
                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-3 text-gray-500 focus:outline-none">😖</button>
                </div>
            </div>

            <!-- Remember + Forgot -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600 gap-2">
                <label class="inline-flex items-center">
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Lupa Password?</a>
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition font-medium">
                Masuk
            </button>

            <!-- Social Login -->
            <div class="text-center text-sm text-gray-500 mt-6">Atau Masuk dengan</div>
            <div class="flex justify-center space-x-4 mt-4">
                <a href="{{ url('auth/google') }}"
                    class="bg-white border px-4 py-2 rounded-lg shadow hover:bg-gray-100 flex items-center text-sm sm:text-base">
                    <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png"
                        class="w-5 h-5 mr-2" alt="Google" />
                    <span class="text-gray-700">Google</span>
                </a>
            </div>

            <!-- Link Register -->
            <div class="text-center mt-6 text-sm text-gray-600">
                Belum punya akun?
                <a href="/Daftar" class="text-blue-600 hover:underline font-medium">Daftar</a>
            </div>
        </form>
    </div>
</div>


@endsection
