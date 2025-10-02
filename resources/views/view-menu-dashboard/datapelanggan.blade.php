@extends('admin.main')
@section('judul', 'Data Pelanggan | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang ')
@section('content')
    <div class="p-6 bg-gray-100 ">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Data Pelanggan</h1>
        </div>

        {{-- Pencarian & Aksi --}}
        <div
            class="bg-white p-4 rounded-lg shadow-sm mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <form action="{{ route('pelanggan.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto mt-1">
                <input type="text" name="search" placeholder="Cari nama atau email..."
                    class="w-full sm:w-64 px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    value="{{ request('search') }}" />
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition duration-200">Cari</button>
            </form>
            {{-- <button onclick="openModal('createModal')"
                class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md font-medium hover:bg-green-700 transition duration-200">
                + Tambah Pelanggan
            </button> --}}
        </div>

        {{-- Tabel --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No HP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total
                            Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total
                            Belanja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $customer->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $customer->orders_count ?? 0 }}x</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp
                                {{ number_format($customer->orders_sum_total ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $customer->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                {{-- Tombol Edit --}}
                                <button onclick='openEditModal(@json($customer))' title="Edit"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                {{-- Tombol Hapus (dengan modal) --}}
                                {{-- <button onclick="openDeleteModal({{ $customer->id }})" title="Hapus"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.035 21H7.965a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9H7" />
                                    </svg>
                                </button> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data pelanggan
                                yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- Modal Create --}}
    <div id="createModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Pelanggan</h2>
                <button type="button" onclick="closeModal('createModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="createName" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="createName" name="name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <div>
                    <label for="createEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="createEmail" name="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <div>
                    <label for="createPhone" class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" id="createPhone" name="phone"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        required>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-green-600 text-white font-medium hover:bg-green-700 transition duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Edit Pelanggan</h2>
                <button type="button" onclick="closeModal('editModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="editName" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="editName"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="editEmail"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="editPhone" class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="phone" id="editPhone"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">Update</button>
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
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Pelanggan?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus data pelanggan ini? Tindakan ini tidak dapat dibatalkan.
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
            }, 300);
        }

        function openEditModal(customer) {
            document.getElementById('editForm').action = `/pelanggan/${customer.id}`;
            document.getElementById('editName').value = customer.name;
            document.getElementById('editEmail').value = customer.email;
            document.getElementById('editPhone').value = customer.phone;
            openModal('editModal');
        }

        function openDeleteModal(customerId) {
            document.getElementById('deleteForm').action = `/pelanggan/${customerId}`;
            openModal('deleteModal');
        }
    </script>
@endsection
