@extends('admin.main')
@section('judul', 'Keranjang Pelanggan | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 ">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Keranjang Pelanggan</h1>

        {{-- Tabel Keranjang --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama
                                Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Subtotal</th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($carts as $cart)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cart->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $cart->product->nama_produk }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $cart->jumlah }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp
                                    {{ number_format($cart->product->harga * $cart->jumlah, 0, ',', '.') }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openDeleteModal({{ $cart->id }})" title="Hapus"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.035 21H7.965a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9H7" />
                                        </svg>
                                    </button>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Keranjang kosong.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-transform duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="bg-red-100 p-3 rounded-full mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Item dari Keranjang?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus item ini dari keranjang pelanggan? Tindakan ini tidak dapat
                    dibatalkan.
                </p>
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 transition duration-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(cartId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/cart/${cartId}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    </script>
@endsection
