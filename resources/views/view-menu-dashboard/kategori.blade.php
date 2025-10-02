@extends('admin.main')
@section('judul', 'Kategori Produk | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Daftar Kategori</h2>
            <button onclick="openModal('modalAdd')"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="fas fa-plus mr-2"></i>Tambah Kategori
            </button>
        </div>


        <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-200 text-left text-gray-700 uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold text-center">#</th>
                        <th class="p-4 font-semibold">Nama Kategori</th>
                        <th class="p-4 font-semibold">Icon</th>
                        <th class="p-4 font-semibold">Deskripsi</th>
                        <th class="p-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($kategoris as $kategori)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="p-4 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4">{{ $kategori->nama_kategori }}</td>
                            <td class="p-4">
                                @if ($kategori->icon)
                                    <img src="{{ asset(Storage::url($kategori->icon)) }}"
                                        class="w-10 h-10 object-cover rounded" alt="icon">
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="p-4">{{ $kategori->deskripsi }}</td>
                            <td class="p-4 flex gap-3 items-center">
                                {{-- Tombol Edit --}}
                                <button onclick="openEditModal({{ $kategori }})" title="Edit"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                {{-- Tombol Hapus (dengan modal) --}}
                                <button onclick="openDeleteModal({{ $kategori->id }})" title="Hapus"
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
                            <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada kategori yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Kategori --}}
    <div id="modalAdd"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800">Tambah Kategori Baru</h3>
            <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="addName" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori:</label>
                    <input type="text" name="nama_kategori" id="addName" placeholder="Masukkan Nama Kategori"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required>
                </div>
                <div class="mb-4">
                    <label for="addIcon" class="block text-gray-700 text-sm font-bold mb-2">Icon Kategori:</label>
                    <input type="file" name="icon" id="addIcon"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div class="mb-6">
                    <label for="addDescription" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                    <textarea name="deskripsi" id="addDescription" rows="3" placeholder="Deskripsi singkat tentang kategori"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalAdd')"
                        class="px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kategori --}}
    <div id="modalEdit"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800">Edit Kategori</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori:</label>
                    <input type="text" name="nama_kategori" id="editName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required>
                </div>
                <div class="mb-4">
                    <label for="editIcon" class="block text-gray-700 text-sm font-bold mb-2">Ganti Icon (opsional):</label>
                    <input type="file" name="icon" id="editIcon"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div class="mb-4" id="currentIconContainer" style="display: none;">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Icon Saat Ini:</label>
                    <img id="currentIconImage" src="" class="w-20 h-20 object-cover rounded border"
                        alt="Icon Kategori">
                </div>
                <div class="mb-6">
                    <label for="editDescription" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                    <textarea name="deskripsi" id="editDescription" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Kategori --}}
    <div id="modalDelete"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            <h3 class="text-2xl font-semibold mb-6 text-red-600">Konfirmasi Hapus</h3>
            <p class="mb-6 text-gray-700 text-base">Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak
                dapat dibatalkan.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalDelete')"
                        class="px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
            document.body.classList.remove('overflow-hidden'); // Allow scrolling
        }

        function openEditModal(kategori) {
            openModal('modalEdit');

            document.getElementById('editName').value = kategori.nama_kategori;
            document.getElementById('editDescription').value = kategori.deskripsi ?? '';
            document.getElementById('editForm').action = `{{ url('kategori') }}/${kategori.id}`;

            if (kategori.icon) {
                document.getElementById('currentIconImage').src = `/storage/${kategori.icon}`;
                document.getElementById('currentIconContainer').style.display = 'block';
            } else {
                document.getElementById('currentIconContainer').style.display = 'none';
            }
        }


        function openDeleteModal(id) {
            openModal('modalDelete');
            document.getElementById('deleteForm').action = `{{ url('kategori') }}/${id}`;
        }

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });
        });
    </script>

    <style>
        /* Keyframe animation for modal entry */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
            /* 'forwards' keeps the end state */
        }
    </style>
@endsection
