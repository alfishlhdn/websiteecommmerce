@extends('layouts.main')

@section('judul', '500 Internal Server Error | AGA IT COMPUTER')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 text-gray-800 p-4 sm:p-8">

    <img src="/image/404.png" alt="500 Internal Server Error Illustration" class="w-64 sm:w-80 mb-8 animate-pulse-slow">

    <h1 class="text-6xl sm:text-8xl font-extrabold text-red-600 mb-4">500</h1>
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-4">Terjadi Kesalahan Server</h2>
    <p class="text-lg sm:text-xl text-center text-gray-600 max-w-lg mb-8">
        Mohon maaf, ada masalah teknis pada server kami. Tim kami sudah menerima laporan dan sedang berusaha untuk memperbaikinya secepat mungkin.
    </p>

    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="{{ url('/') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
