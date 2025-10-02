@extends('admin.main')
@section('judul', 'Kelola Pengiriman | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Kurir Pengiriman</h1>

        {{-- Perubahan di sini: Menggunakan `justify-end` untuk memposisikan item di kanan --}}
        <div class="flex justify-end items-center mt-4 mb-6">
            <button onclick="openCreateModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Kurir
            </button>
        </div>

        <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Kurir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe
                            Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ongkos
                            Kirim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kurirs as $index => $k)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $k->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $k->service_code }} - {{ $k->service_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $k->keterangan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                {{ number_format($k->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick="openEditModal({{ json_encode($k) }})"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $k->id }})"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada kurir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-start justify-center z-50 pt-16">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-2xl font-bold text-gray-800">Tambah Kurir</h3>
                <button type="button" onclick="closeModal('createModal')"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('kurir.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="create_name" class="block text-sm font-medium text-gray-700">Nama Kurir</label>
                    <input type="text" name="name" id="create_name"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="create_service_type" class="block text-sm font-medium text-gray-700">Tipe Layanan</label>
                    <input type="text" name="service_type" id="create_service_type"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="create_price" class="block text-sm font-medium text-gray-700">Ongkos Kirim</label>
                    <input type="number" name="price" id="create_price"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-start justify-center z-50 pt-16">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-2xl font-bold text-gray-800">Edit Kurir</h3>
                <button type="button" onclick="closeModal('editModal')"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            <form id="editForm" method="POST" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kurir</label>
                    <input type="text" name="name" id="edit_name"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="edit_service_type" class="block text-sm font-medium text-gray-700">Tipe Layanan</label>
                    <input type="text" name="service_type" id="edit_service_type"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_price" class="block text-sm font-medium text-gray-700">Ongkos Kirim</label>
                    <input type="number" name="price" id="edit_price"
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-start justify-center z-50 pt-16">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Hapus Kurir</h3>
            <p class="text-gray-600">Anda yakin ingin menghapus kurir ini? Aksi ini tidak dapat dibatalkan.</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal('deleteModal')"
                    class="px-4 py-2 rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300">Batal</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal dengan transisi
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Mencegah scrolling di background
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('div').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Fungsi untuk menutup modal dengan transisi
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.querySelector('div').classList.remove('scale-100', 'opacity-100');
            modal.querySelector('div').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Mengaktifkan kembali scrolling
            }, 300);
        }

        // Fungsi untuk memicu modal Tambah
        function openCreateModal() {
            openModal('createModal');
        }

        // Fungsi untuk memicu modal Edit dan mengisi form
        function openEditModal(kurir) {
            document.getElementById('edit_id').value = kurir.id;
            document.getElementById('edit_name').value = kurir.name;
            document.getElementById('edit_service_type').value = kurir.service_type;
            document.getElementById('edit_price').value = kurir.price;
            document.getElementById('editForm').action = `{{ url('kurir') }}/${kurir.id}`;

            openModal('editModal');
        }

        // Fungsi untuk memicu modal Hapus
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `{{ url('kurir') }}/${id}`;
            openModal('deleteModal');
        }

        // Menutup modal saat mengklik di luar area modal
        document.addEventListener('DOMContentLoaded', () => {
            const modals = ['createModal', 'editModal', 'deleteModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                modal.addEventListener('click', (e) => {
                    if (e.target.id === modalId) {
                        closeModal(modalId);
                    }
                });
            });
        });
    </script>
@endsection
