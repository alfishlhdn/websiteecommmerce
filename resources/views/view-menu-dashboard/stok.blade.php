@extends('admin.main')
@section('judul', 'Stok - Inventori Produk | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-900 m-6">Stok / Inventori Produk</h1>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Tambah Stok --}}
                <button onclick="openCreateModal()"
                    class="flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Tambah Stok</span>
                </button>


                {{-- Tombol Import dan Export --}}
                <div class="flex items-center gap-3">
                    <form action="{{ route('stok.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex items-center gap-2" id="form-import">
                        @csrf
                        <label for="file-import"
                            class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-md font-medium hover:bg-green-700 transition duration-200 cursor-pointer">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span>Import Excel</span>
                        </label>
                        <input type="file" name="file" id="file-import" required class="sr-only">
                    </form>

                    <script>
                        const fileInput = document.getElementById('file-import');
                        const form = document.getElementById('form-import');

                        fileInput.addEventListener('change', function() {
                            if (fileInput.files.length > 0) {
                                form.submit(); // otomatis submit saat file dipilih
                            }
                        });
                    </script>

                    <a href="{{ route('stok.export') }}"
                        class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700 transition duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>
        </div>

        @if (session('import_errors'))
            <div class="rounded-md bg-yellow-50 border border-yellow-200 text-yellow-900 px-4 py-3">
                <div class="font-semibold mb-1">Detail baris yang dilewati:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach (session('import_errors') as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($stocks as $stock)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $stock->product->nama_produk }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $stock->product->category->nama_kategori ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($stock->tipe == 'masuk')
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Masuk</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">Keluar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stock->jumlah }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $stock->keterangan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                    <button onclick="openEditModal({{ $stock }})" title="Edit"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal({{ $stock->id }})" title="Hapus"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.035 21H7.965a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9H7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div id="createModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Stok</h2>
                <button type="button" onclick="closeModal('createModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('stok.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                    <select name="product_id" id="product_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="tipe" id="tipe"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Edit Stok</h2>
                <button type="button" onclick="closeModal('editModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="editProduct" class="block text-sm font-medium text-gray-700">Produk</label>
                    <select name="product_id" id="editProduct"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="editTipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="tipe" id="editTipe"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>
                <div>
                    <label for="editJumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" id="editJumlah"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                </div>
                <div>
                    <label for="editKeterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">
                        Update
                    </button>
                </div>
            </form>
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
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Stok?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus data stok ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeModal('deleteModal')"
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
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        function openCreateModal() {
            openModal('createModal');
        }

        function openEditModal(stock) {
            let url = `{{ route('stok.update', ':id') }}`;
            url = url.replace(':id', stock.id);

            document.getElementById('editForm').action = url;
            document.getElementById('editProduct').value = stock.product_id;
            document.getElementById('editTipe').value = stock.tipe;
            document.getElementById('editJumlah').value = stock.jumlah;
            document.getElementById('editKeterangan').value = stock.keterangan;

            openModal('editModal');
        }

        function openDeleteModal(id) {
            let url = `{{ route('stok.destroy', ':id') }}`;
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
            openModal('deleteModal');
        }

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target.id === modal.id) {
                        closeModal(modal.id);
                    }
                });
            });
        });
    </script>
@endsection
