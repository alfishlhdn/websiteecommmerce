@extends('admin.main')
@section('judul', 'Brand Produk | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')
@section('content')
    <div class="p-6 mt-5"> {{-- Added background color and min-height --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Daftar Brand</h2> {{-- Increased font size --}}
            <button onclick="openModal('modalAdd')"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                {{-- More rounded, shadow, transition, and focus styles --}}
                <i class="fas fa-plus mr-2"></i>Tambah Brand {{-- Added Font Awesome icon --}}
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-lg"> {{-- Larger shadow and rounded corners --}}
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-200 text-left text-gray-700 uppercase tracking-wider"> {{-- Lighter header background and clearer text --}}
                    <tr>
                        <th class="p-4 font-semibold text-center">#</th> {{-- Increased padding and bolded --}}
                        <th class="p-4 font-semibold">Nama</th>
                        <th class="p-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($brands as $brand)
                        {{-- Using @forelse for empty state --}}
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"> {{-- Smooth hover effect --}}
                            <td class="p-4 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4">{{ $brand->name }}</td>
                            <td class="p-4 flex gap-3 items-center">
                                {{-- Tombol Edit --}}
                                <button onclick="openEditModal({{ $brand }})" title="Edit"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                {{-- Tombol Hapus (dengan modal) --}}
                                <button onclick="openDeleteModal({{ $brand->id }})" title="Hapus"
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
                            <td colspan="3" class="p-4 text-center text-gray-500">Tidak ada brand yang terdaftar.</td>
                            {{-- Empty state message --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalAdd"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        {{-- Darker overlay, centered, transition --}}
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            {{-- Larger padding, rounder, bigger shadow, smoother animation --}}
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Tambah Brand Baru</h3> {{-- Clearer heading --}}
            <form action="{{ route('brand.store') }}" method="POST">
                @csrf
                <div class="mb-4"> {{-- Added div for spacing --}}
                    <label for="addName" class="block text-gray-700 text-sm font-bold mb-2">Nama Brand:</label>
                    <input type="text" name="name" id="addName" placeholder="Masukkan Nama Brand"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required> {{-- Enhanced input styling --}}
                </div>
                <div class="flex justify-end gap-3"> {{-- Increased gap between buttons --}}
                    <button type="button" onclick="closeModal('modalAdd')"
                        class="px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Edit Brand</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700 text-sm font-bold mb-2">Nama Brand:</label>
                    <input type="text" name="name" id="editName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required>
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

    <div id="modalDelete"
        class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-out animate-fade-in-up">
            <h3 class="text-xl font-semibold mb-6 text-red-600">Konfirmasi Hapus</h3>
            <p class="mb-6 text-gray-700 text-base">Apakah Anda yakin ingin menghapus brand ini? Tindakan ini tidak dapat
                dibatalkan.</p> {{-- More explicit warning --}}
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
            // Optional: Add a class to the body to prevent scrolling when modal is open
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
            // Optional: Remove the class from the body
            document.body.classList.remove('overflow-hidden');
        }

        function openEditModal(brand) {
            openModal('modalEdit');
            document.getElementById('editName').value = brand.name;
            document.getElementById('editForm').action =
                `{{ url('brand') }}/${brand.id}`; // Ensure absolute path for action
        }

        function openDeleteModal(id) {
            openModal('modalDelete');
            document.getElementById('deleteForm').action =
                `{{ url('brand') }}/${id}`; // Ensure absolute path for action
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
