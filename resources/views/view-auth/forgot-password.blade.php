@extends('Layouts.main')

@section('judul', 'Lupa Password - AGA IT COMPUTER | Toko Komputer & Service Laptop Malang')

@section('content')
    <div class="flex items-center justify-center bg-gray-100 p-4 sm:p-6">
        <div class="max-w-md w-full bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-200">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Lupa Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Masukkan email Anda untuk menerima tautan reset password.
                </p>
            </div>

            {{-- @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-md mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif --}}

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-md mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input id="email" type="email" name="email" required autocomplete="email" autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="contoh@email.com">
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out">
                        Kirim Tautan Reset
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('Masuk') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Kembali ke halaman login
                </a>
            </div>
        </div>
    </div>
@endsection
