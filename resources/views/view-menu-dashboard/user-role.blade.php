@extends('admin.main')
@section('judul', 'Manajemen User | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen mt-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-shield text-blue-600 mr-3 "></i> Manajemen User
            </h1>
            @if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
                <button onclick="openAddModal()"
                    class="inline-flex items-center px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Tambah User
                </button>
            @endif
        </div>

        <div class="border-t border-gray-300 my-6"></div>

        {{-- Tabel Daftar Pengguna --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Telepon</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->role == 'superadmin')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-crown mr-1"></i> Super Admin
                                        </span>
                                    @elseif($user->role == 'admin')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-cog mr-1"></i> Admin
                                        </span>
                                    @elseif($user->role == 'client')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-id-badge mr-1"></i> Client
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                            <i class="fas fa-user mr-1"></i> User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->phone ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openEditModal({{ json_encode($user) }})" title="Edit"
                                            class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="openDeleteModal({{ $user->id }})" title="Hapus"
                                            class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah User --}}
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Tambah User Baru</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('user-role.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="add-name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="add-name" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>
                <div>
                    <label for="add-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="add-email" name="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>
                <div>
                    <label for="add-password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="add-password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>
                <div>
                    <label for="add-phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" id="add-phone" name="phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="add-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="add-role" name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="user">User</option>
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit User --}}
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
                <button onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="edit-name" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>
                <div>
                    <label for="edit-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="edit-email" name="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>
                <div>
                    <label for="edit-phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" id="edit-phone" name="phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="edit-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="edit-role" name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="user">User</option>
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label for="edit-password" class="block text-sm font-medium text-gray-700">
                        Password Baru <small class="text-gray-400">(Opsional)</small>
                    </label>
                    <input type="password" id="edit-password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        placeholder="Kosongkan jika tidak ingin mengganti">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus User --}}
    <div id="deleteModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-transform duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="bg-red-100 p-3 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus User?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.
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
                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openEditModal(user) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            form.action = `/user-role/${user.id}`;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-phone').value = user.phone;
            document.getElementById('edit-role').value = user.role;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openDeleteModal(userId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/user-role/${userId}`;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        window.addEventListener('click', function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');

            if (event.target === addModal) {
                closeAddModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
