@extends('Auth.main')
@section('judul', 'Set Password - AGA IT COMPUTER | Toko Komputer & Service Laptop Malang')
@section('content')
    <div class="max-w-md mx-auto mt-12 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-semibold mb-4">Atur Password Akun Anda</h2>

        {{-- @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif --}}
        @if (session('info'))
            <div class="bg-blue-100 text-blue-800 p-3 rounded mb-4">{{ session('info') }}</div>
        @endif

        <p class="mb-4 text-sm text-gray-600">Akun Anda sudah dibuat menggunakan Google. Buat password agar nantinya Anda
            bisa masuk tanpa Google.</p>

        <form action="{{ route('password.set.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Password baru</label>
                <input type="password" name="password" minlength="8" class="w-full border p-2 rounded" />
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Konfirmasi password</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded" />
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded">Simpan Password</button>

                <form action="{{ route('password.set.skip') }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="py-2 px-4 border rounded">Lewati</button>
                </form>
            </div>
        </form>
    </div>
@endsection
