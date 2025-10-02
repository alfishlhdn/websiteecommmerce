@extends('layouts.main')

@section('judul', '404 Not Found | AGA IT COMPUTER')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 text-gray-800 p-4 sm:p-8">

    <img src="/image/404.png" alt="404 Not Found Illustration" class="w-64 sm:w-80 mb-8 animate-bounce-slow">

    <h1 class="text-6xl sm:text-8xl font-extrabold text-blue-600 mb-4">404</h1>
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-4">Halaman Tidak Ditemukan</h2>
    <p class="text-lg sm:text-xl text-center text-gray-600 max-w-lg mb-8">
        Maaf, halaman yang Anda cari tidak dapat kami temukan. Mungkin tautannya rusak, atau halaman sudah dipindahkan.
    </p>

    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="{{ url('/') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
            Kembali ke Beranda
        </a>

    </div>
</div>
@endsection
