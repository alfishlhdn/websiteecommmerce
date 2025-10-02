@extends('layouts.main')

@section('judul', '403 Forbidden | AGA IT COMPUTER')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 text-gray-800 p-4 sm:p-8">

    <img src="/image/404.png" alt="403 Forbidden Illustration" class="w-64 sm:w-80 mb-8 animate-shake">

    <h1 class="text-6xl sm:text-8xl font-extrabold text-yellow-600 mb-4">403</h1>
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-4">Akses Ditolak</h2>
    <p class="text-lg sm:text-xl text-center text-gray-600 max-w-lg mb-8">
        Maaf, Anda tidak memiliki izin untuk mengakses halaman yang Anda tuju.
        Silakan kembali ke halaman utama atau coba masuk dengan akun yang memiliki hak akses.
    </p>

    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="{{ url('/') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
