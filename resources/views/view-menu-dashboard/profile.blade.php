<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Custom CSS untuk transisi modal */
        #editProfileModal {
            transition: opacity 0.3s ease-out;
        }

        #editProfileModal>div {
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        .modal-hidden {
            opacity: 0;
            pointer-events: none;
        }

        .modal-content-hidden {
            transform: scale(0.9);
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-900 text-white p-4 flex justify-between items-center shadow-md">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('image/agaitcomputer.png') }}" alt="Logo" class="h-10 w-10 rounded-full">
            <span class="text-xl font-bold">AGA IT COMPUTER</span>
        </div>
        <ul class="flex space-x-6">
            <li><a href="/dashboard" class="hover:text-blue-200 transition-colors">Dashboard</a></li>
            <li><a href="/produk" class="hover:text-blue-200 transition-colors">Produk</a></li>
            <li><a href="/pesanan" class="hover:text-blue-200 transition-colors">Pesanan</a></li>
            <li class="relative">
                <button id="profileDropdownBtn" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ asset('image/agaitcomputer.png') }}" alt="User Avatar"
                        class="h-8 w-8 rounded-full border-2 border-white">
                    <span class="hidden md:inline-block">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </button>
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden">
                    <a href="/pengaturan-toko" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"><i
                            class="fas fa-cog mr-2"></i> Pengaturan</a>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50"><i
                                class="fas fa-sign-out-alt mr-2"></i> Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <div class="container mx-auto p-8 mt-10">
        <div
            class="bg-white rounded-lg shadow-xl p-8 max-w-xl mx-auto transform transition-all duration-300 hover:shadow-2xl">
            <div class="text-center">
                <img src="{{ asset('image/logoagaitcomputer.png') }}" alt="User Avatar"
                    class="w-32 h-32 rounded-full mx-auto border-4 border-blue-500 object-cover">
                <h1 class="text-3xl font-bold text-gray-800 mt-4">{{ $user->name }}</h1>
                <p class="text-lg text-gray-600">{{ $user->email }}</p>
                <span
                    class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mt-2">{{ ucfirst($user->role) }}</span>

                <p class="text-gray-500 mt-4">{{ $user->phone ?? 'Nomor telepon belum diisi.' }}</p>

                <button id="openModalBtn"
                    class="mt-6 px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition duration-300 shadow-lg">
                    Edit Profil
                </button>
            </div>
        </div>
    </div>

    <div id="editProfileModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 modal-hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md mx-4 modal-content-hidden">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-800 transition-colors">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ $user->phone }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru
                        <span class="text-gray-500 font-normal">(opsional)</span></label>
                    <input type="password" id="password" name="password"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Kosongkan jika tidak ingin ganti">
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" id="cancelBtn"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const modal = document.getElementById('editProfileModal');
        const modalContent = modal.querySelector('div');

        function openModal() {
            modal.classList.remove('modal-hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modalContent.classList.remove('modal-content-hidden');
            }, 10);
        }

        function closeModal() {
            modalContent.classList.add('modal-content-hidden');
            setTimeout(() => {
                modal.classList.add('modal-hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');

        profileDropdownBtn.addEventListener('click', () => {
            profileDropdownMenu.classList.toggle('hidden');
            profileDropdownBtn.querySelector('i').classList.toggle('rotate-180');
        });

        window.addEventListener('click', (e) => {
            if (!profileDropdownBtn.contains(e.target) && !profileDropdownMenu.contains(e.target)) {
                profileDropdownMenu.classList.add('hidden');
                profileDropdownBtn.querySelector('i').classList.remove('rotate-180');
            }
        });
    </script>

</body>

</html>
