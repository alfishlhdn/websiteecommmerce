@extends('admin.main')
@section('judul', 'Pengaturan Toko | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 ">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Pengaturan Toko</h1>

        <div class="bg-white shadow-lg rounded-xl p-6">
            {{-- Tombol Tab --}}
            <div class="flex border-b border-gray-200 mb-6">
                <button onclick="switchTab('info', this)" id="tab-btn-info"
                    class="tab-btn px-4 py-2 -mb-px text-lg font-semibold text-gray-700 border-b-2 border-transparent hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200">
                    Informasi Umum
                    <button onclick="switchTab('banner', this)" id="tab-btn-banner"
                        class="tab-btn px-4 py-2 -mb-px text-lg font-semibold text-gray-700 border-b-2 border-transparent hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200">
                        Manajemen Banner
                    </button>
                </button>
            </div>

            {{-- Konten Tab Informasi Umum --}}
            <div id="tab-info" class="tab-content">
                <form method="POST" action="{{ route('store_settings.updateInfo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" id="store_name" name="store_name"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('store_name', $setting->store_name ?? '') }}">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('email', $setting->email ?? '') }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <input type="text" id="phone" name="phone"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('phone', $setting->phone ?? '') }}">
                        </div>
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" id="logo" name="logo"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if (!empty($setting->logo))
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500">Logo saat ini:</p>
                                    <img src="{{ asset('storage/' . $setting->logo) }}"
                                        class="h-20 w-auto object-contain mt-1 rounded-md border p-1 bg-gray-50">
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea id="address" name="address" rows="3"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('address', $setting->address ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="address_ingooglemaps" class="block text-sm font-medium text-gray-700">Link Alamat
                                Google Maps </label>
                            <input type="link" id="address_ingooglemaps" name="address_ingooglemaps"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('address_ingooglemaps', $setting->address_ingooglemaps ?? '') }}">
                        </div>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Konten Tab Banner --}}
            <div id="tab-banner" class="tab-content hidden">
                <form method="POST" action="{{ route('store_settings.addBanner') }}" enctype="multipart/form-data"
                    class="space-y-4 mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    @csrf
                    <h3 class="text-xl font-bold text-gray-800">Tambah Banner Baru</h3>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Banner</label>
                        <input type="text" id="title" name="title"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Banner</label>
                        <input type="file" id="image" name="image"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="description" name="description" rows="2"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label for="link" class="block text-sm font-medium text-gray-700">Link URL</label>
                        <input type="text" id="link" name="link"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Aktif</label>
                    </div>
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-semibold rounded-md shadow-sm hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Tambah Banner
                    </button>
                </form>

                <h3 class="text-xl font-bold text-gray-800 mb-4">Banner Aktif</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($banners as $banner)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm overflow-hidden relative group">
                            @if ($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner {{ $banner->title }}"
                                    class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-105">
                            @endif
                            <div class="p-4">
                                <h1 class="text-bold text-gray-700 mb-3">Judul : {{ $banner->title }}</h1>
                                <form method="POST" action="{{ route('store_settings.toggleBanner', $banner->id) }}"
                                    class="mt-3 px-4 pb-4">
                                    @csrf

                                    <p>status</p>
                                    <button type="submit"
                                        class="text-xs px-3 py-1 rounded-full font-semibold
                                        {{ $banner->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <button onclick="openEditModal({{ $banner }})" title="Edit"
                                    class="absolute top-3 left-3 p-2 bg-white rounded-full shadow-md text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6 6M4 13l5 5a2 2 0 0 0 2.828 0l10-10a2 2 0 0 0-2.828-2.828L9 15l-5-5z" />
                                    </svg>
                                </button>
                                <p class="text-sm text-gray-700 mb-3"> Deskripsi {{ $banner->description }}</p>
                                @if ($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank"
                                        class="text-blue-600 text-sm hover:underline truncate block">
                                        {{ $banner->link }}
                                    </a>
                                @endif
                            </div>
                            <button onclick="openDeleteModal({{ $banner->id }})" title="Hapus"
                                class="absolute top-3 right-3 p-2 bg-white rounded-full shadow-md text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.035 21H7.965a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9H7" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 col-span-full text-center">Belum ada banner ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform scale-95 transition-transform duration-300">
            <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Edit Banner</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Banner</label>
                        <input type="text" name="title" id="editTitle"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="editDescription" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link URL</label>
                        <input type="text" name="link" id="editLink"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ganti Gambar (opsional)</label>
                        <input type="file" name="image"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="editIsActive"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="editIsActive" class="ml-2 text-sm text-gray-700">Aktif</label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
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
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Banner?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus banner ini? Tindakan ini tidak dapat dibatalkan.
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
        const tabs = {
            'info': document.getElementById('tab-info'),
            'banner': document.getElementById('tab-banner')
        };

        const tabButtons = {
            'info': document.getElementById('tab-btn-info'),
            'banner': document.getElementById('tab-btn-banner')
        };

        function switchTab(tabName, clickedButton) {
            // Sembunyikan semua tab
            for (const key in tabs) {
                tabs[key].classList.add('hidden');
            }
            // Nonaktifkan semua tombol
            for (const key in tabButtons) {
                tabButtons[key].classList.remove('border-blue-600', 'text-blue-600');
                tabButtons[key].classList.add('border-transparent', 'text-gray-700');
            }

            // Tampilkan tab yang dipilih
            tabs[tabName].classList.remove('hidden');

            // Aktifkan tombol yang dipilih
            clickedButton.classList.remove('border-transparent', 'text-gray-700');
            clickedButton.classList.add('border-blue-600', 'text-blue-600');
        }

        // Modal delete functions
        function openDeleteModal(bannerId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('/pengaturan-toko/banner') }}/${bannerId}`;
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

        // Inisialisasi tab
        document.addEventListener('DOMContentLoaded', () => {
            // Tentukan tab mana yang harus aktif saat pertama kali dimuat
            const initialTab = '{{ $errors->any() && old('title') ? 'banner' : 'info' }}';
            switchTab(initialTab, tabButtons[initialTab]);
        });


        function openEditModal(banner) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            // Set form action
            form.action = `/store_settings/banner/${banner.id}`;

            // Prefill data
            document.getElementById('editTitle').value = banner.title || '';
            document.getElementById('editDescription').value = banner.description || '';
            document.getElementById('editLink').value = banner.link || '';
            document.getElementById('editIsActive').checked = banner.is_active;

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
@endsection
