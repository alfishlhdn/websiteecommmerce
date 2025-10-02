@extends('admin.main')

@section('judul', 'Manajemen Produk | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 mt-5"> {{-- Warna latar belakang dan tinggi minimum --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Produk</h1> {{-- Judul lebih besar dan bold --}}
            <button onclick="openModal('create')"
                class="flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
            </button>
        </div>

        <div
            class="bg-white shadow-xl rounded-lg p-6 mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
            <form method="GET" class="w-full md:w-1/2">
                <div class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <button type="submit" class="hidden">Cari</button>
                </div>
            </form>

            <div class="flex items-center w-full md:w-auto justify-end space-x-4">
                <form method="GET">
                    <select name="sort" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                        <option value="">Urutkan</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah
                        </option>
                        <option value="status_aktif" {{ request('sort') == 'status_aktif' ? 'selected' : '' }}>Status: Aktif
                        </option>
                        <option value="status_nonaktif" {{ request('sort') == 'status_nonaktif' ? 'selected' : '' }}>Status:
                            Nonaktif</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi
                        </option>
                    </select>
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>
        </div>


        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama
                            Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($products as $index => $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->foto)
                                    <img src="{{ asset(Storage::url($product->foto)) }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-sm" alt="Produk Gambar" />
                                @else
                                    <span class="text-gray-400 text-xs italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $product->nama_produk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->stok }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- Debug diskon --}}
                                {{-- <pre>
                                {{ json_encode($product->discounts, JSON_PRETTY_PRINT) }}
                                </pre> --}}
                                @php
                                    $discount = $product->discounts
                                        ->filter(function ($d) {
                                            return $d->type === 'product' &&
                                                $d->status == 1 &&
                                                (is_null($d->expired_at) || $d->expired_at >= now());
                                        })
                                        ->first();

                                    $hargaAsli = $product->harga;
                                    $hargaDiskon = $hargaAsli;

                                    if ($discount) {
                                        if ($discount->discount_type === 'percent') {
                                            $hargaDiskon = $hargaAsli - $hargaAsli * ($discount->value / 100);
                                        } elseif ($discount->discount_type === 'nominal') {
                                            $hargaDiskon = max(0, $hargaAsli - $discount->value);
                                        }
                                    }
                                @endphp

                                @if ($discount)
                                    <span class="text-gray-400 line-through mr-2">
                                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                    </span>
                                    <span class="text-green-600 font-semibold">
                                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                    </span>
                                    <span class="ml-1 text-xs text-red-500">
                                        Diskon :
                                        ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                    </span>
                                @else
                                    <span class="text-green-600 font-semibold">
                                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                    </span>
                                @endif

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $product->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-3 items-center">
                                {{-- Tombol Detail (dengan modal) --}}
                                <button data-modal-target="#detailModal-{{ $product->id }}" title="Detail"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                {{-- Tombol Edit --}}
                                <button data-modal-target="#editModal-{{ $product->id }}" title="Edit"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                {{-- Tombol Hapus (dengan modal) --}}
                                <button onclick="confirmDelete({{ $product->id }})" title="Hapus"
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
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links('pagination::tailwind') }} {{-- Menggunakan Tailwind CSS untuk pagination --}}
        </div>
    </div>


    @foreach ($products as $product)
        <div id="editModal-{{ $product->id }}"
            class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-start justify-center pt-24 z-50 transition-opacity duration-300 ease-out opacity-0">
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-3xl transform -translate-y-4 scale-95 transition-all duration-300 ease-out max-h-[90vh] flex flex-col">

                {{-- SCROLLABLE CONTENT --}}
                <div class="p-8 overflow-y-auto" style="max-height: calc(90vh - 80px);">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Produk</h2>

                    <form id="form-edit-{{ $product->id }}" action="{{ route('produk.update', $product->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="nama_produk_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                                <input type="text" name="nama_produk" id="nama_produk_{{ $product->id }}"
                                    value="{{ $product->nama_produk }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                            </div>
                            <div>
                                <label for="harga_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <input type="number" name="harga" id="harga_{{ $product->id }}"
                                    value="{{ $product->harga }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                            </div>
                            <div>
                                <label for="stok_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                                <input type="number" name="stok" id="stok_{{ $product->id }}"
                                    value="{{ $product->stok }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                            </div>
                            <div>
                                <label for="berat_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Berat</label>
                                <input type="number" name="berat" id="berat_{{ $product->id }}"
                                    value="{{ $product->berat }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                {{-- harga normal --}}
                                <div>
                                    <label for="harga_{{ $product->id }}"
                                        class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                    <input type="number" name="harga" id="harga_{{ $product->id }}"
                                        value="{{ $product->harga }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                                </div>

                                {{-- harga b2b --}}
                                <div>
                                    <label for="harga_b2b_{{ $product->id }}"
                                        class="block text-sm font-medium text-gray-700 mb-2">Harga B2B</label>
                                    <input type="number" name="harga_b2b" id="harga_b2b_{{ $product->id }}"
                                        value="{{ optional($product->priceList)->price }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                                </div>
                            </div>

                            <div>
                                <label for="status_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="status_{{ $product->id }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    <option value="aktif" {{ $product->status == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ $product->status == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif</option>
                                </select>
                            </div>
                            <div>
                                <label for="category_id_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>

                                <select name="category_id" id="category_id_{{ $product->id }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="brand_id_{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select name="brand_id" class="border rounded px-3 py-2 w-full">
                                    @if ($product->brand)
                                        <option value="{{ $product->brand_id }}">{{ $product->brand->name }}</option>
                                    @else
                                        <option value="" selected>- Brand Tidak Ada (Telah Dihapus)</option>
                                    @endif

                                    @foreach ($brands as $brand)
                                        @if ($brand->id != $product->brand_id)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="deskripsi_{{ $product->id }}"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi_{{ $product->id }}" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">{{ $product->deskripsi }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label for="foto_{{ $product->id }}"
                                class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                            <input type="file" name="foto" id="foto_{{ $product->id }}"
                                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if ($product->foto)
                                <img src="{{ asset(Storage::url($product->foto)) }}"
                                    class="w-24 mt-4 rounded-lg shadow-md border border-gray-200"
                                    alt="Gambar Produk Utama">
                            @endif
                            <small class="mt-2 block text-xs text-gray-500">Direkomendasikan ukuran minimal 1200x1200
                                piksel untuk kualitas terbaik.</small>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi Produk</label>
                            <div id="specifications-{{ $product->id }}" class="space-y-3">
                                @foreach ($product->specifications as $i => $spec)
                                    <div class="flex gap-3 items-center">
                                        <input type="text" name="specifications[{{ $i }}][key]"
                                            value="{{ $spec->key }}"
                                            class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800"
                                            placeholder="Nama Spesifikasi">
                                        <input type="text" name="specifications[{{ $i }}][value]"
                                            value="{{ $spec->value }}"
                                            class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800"
                                            placeholder="Nilai Spesifikasi">
                                        <button type="button" onclick="this.closest('.flex').remove()"
                                            class="text-red-600 hover:text-red-800 text-xl">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addSpec('{{ $product->id }}')"
                                class="text-blue-600 mt-3 text-sm hover:underline">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Spesifikasi
                            </button>
                        </div>
                    </form>

                    <div class="mb-6">
                        <label for="create_images_{{ $product->id }}"
                            class="block text-sm font-medium text-gray-700 mb-2">Gambar
                            Tambahan</label>
                        <input type="file" name="images[]" multiple id="create_images_{{ $product->id }}"
                            class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <small class="mt-2 block text-xs text-gray-500">Direkomendasikan ukuran minimal 1200x1200 piksel
                            untuk kualitas terbaik.</small>
                    </div>

                    {{-- GAMBAR TAMBAHAN + FORM DELETE (DI LUAR FORM UPDATE) --}}
                    <div class="flex flex-wrap gap-3 mt-4">
                        @foreach ($product->images as $img)
                            <div class="relative group">
                                <a href="{{ asset(Storage::url($img->image_path)) }}" target="_blank">
                                    <img src="{{ asset(Storage::url($img->image_path)) }}"
                                        class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm group-hover:scale-105 transition duration-200"
                                        alt="Gambar Tambahan">
                                </a>
                                <form action="{{ route('produk.deleteImage', $img->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus gambar ini?')"
                                    class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- FOOTER TOMBOL --}}
                <div class="flex justify-end space-x-3 p-6 border-t">
                    <button type="button" onclick="closeModalEdit('editModal-{{ $product->id }}')"
                        class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium">Batal</button>
                    <button type="submit" form="form-edit-{{ $product->id }}"
                        class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium">Update</button>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($products as $product)
        <div id="detailModal-{{ $product->id }}"
            class="fixed inset-0 z-50 hidden items-start justify-center pt-24 bg-black bg-opacity-60 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-md w-full max-w-4xl mx-auto p-6 relative">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Detail Produk</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p class="mb-2"><strong>Nama:</strong> {{ $product->nama_produk }}</p>
                        <p class="mb-2"><strong>Harga:</strong>
                            @php
                                $discount = $product->discounts
                                    ->filter(function ($d) {
                                        return $d->type === 'product' &&
                                            $d->status == 1 &&
                                            (is_null($d->expired_at) || $d->expired_at >= now());
                                    })
                                    ->first();

                                $hargaAsli = $product->harga;
                                $hargaDiskon = $hargaAsli;

                                if ($discount) {
                                    if ($discount->discount_type === 'percent') {
                                        $hargaDiskon = $hargaAsli - $hargaAsli * ($discount->value / 100);
                                    } elseif ($discount->discount_type === 'nominal') {
                                        $hargaDiskon = max(0, $hargaAsli - $discount->value);
                                    }
                                }
                            @endphp

                            @if ($discount)
                                <span class="text-gray-400 line-through mr-2">
                                    Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                </span>
                                <span class="text-green-600 font-semibold">
                                    Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                </span>
                                <span class="ml-1 text-xs text-red-500">
                                    Diskon :
                                    ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                </span>
                            @else
                                <span class="text-green-600 font-semibold">
                                    Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                </span>
                            @endif
                        </p>
                        <p class="mb-2">
                            <strong>Harga B2B:</strong>
                            @if ($product->priceList)
                                Rp {{ number_format($product->priceList->price, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">Tidak ada harga B2B</span>
                            @endif
                        </p>

                        <p class="mb-2"><strong>Stok:</strong> {{ $product->stok }}</p>
                        <p class="mb-2"><strong>Status:</strong>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $product->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </p>
                        <p class="mb-2">
                            <strong>Kategori:</strong>
                            @if ($product->category)
                                {{ $product->category->nama_kategori }}
                            @else
                                <span class="text-red-500">- Kategori Tidak Ada (Telah Dihapus)</span>
                            @endif
                        </p>

                        <p class="mb-2">
                            <strong>Brand:</strong>
                            @if ($product->brand)
                                {{ $product->brand->name }}
                            @else
                                <span class="text-red-500">- Brand Tidak Ada (Telah Dihapus)</span>
                            @endif
                        </p>

                    </div>
                    <div>
                        <p class="font-bold mb-2">Deskripsi:</p>
                        <p class="text-justify mb-4">{{ $product->deskripsi }}</p>

                        <p class="font-bold mb-2">Spesifikasi:</p>
                        @forelse ($product->specifications as $spec)
                            <p class="ml-2 mb-1 text-sm"><span class="font-semibold">{{ $spec->key }}:</span>
                                {{ $spec->value }}</p>
                        @empty
                            <p class="ml-2 text-sm text-gray-500">Tidak ada spesifikasi.</p>
                        @endforelse

                        <p class="font-bold mb-2">Berat:</p>
                        <p class="text-justify mb-4">{{ $product->berat }}g ( gram )</p>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="font-bold mb-3">Gambar Produk:</p>
                    <div class="flex flex-wrap gap-4">
                        @if ($product->foto)
                            <div class="relative group">
                                <img src="{{ asset(Storage::url($product->foto)) }}"
                                    class="w-32 h-32 object-cover rounded-lg shadow-md border border-gray-200 group-hover:scale-105 transition duration-200"
                                    alt="Gambar Utama">
                                <span
                                    class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Utama</span>
                            </div>
                        @endif
                        @forelse($product->images as $img)
                            <div class="relative group">
                                <a href="{{ asset(Storage::url($img->image_path)) }}" target="_blank">
                                    <img src="{{ asset(Storage::url($img->image_path)) }}"
                                        class="w-32 h-32 object-cover rounded-lg shadow-md border border-gray-200 group-hover:scale-105 transition duration-200"
                                        alt="Gambar Tambahan">
                                </a>
                            </div>
                        @empty
                            @if (!$product->foto)
                                {{-- Only show if no main image either --}}
                                <p class="text-sm text-gray-500">Tidak ada gambar untuk produk ini.</p>
                            @endif
                        @endforelse

                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button onclick="closeModalDetail('detailModal-{{ $product->id }}')"
                        class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium transition duration-200 ease-in-out mt-5">Tutup</button>
                </div>
            </div>
        </div>
    @endforeach


    <div id="productModal"
        class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-start justify-center pt-24 z-50 transition-opacity duration-300 ease-out opacity-0">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-2xl transform -translate-y-4 scale-95 transition-all duration-300 ease-out overflow-y-auto max-h-[90vh] flex flex-col">
            {{-- SCROLLABLE CONTENT --}}
            <div class="p-8 overflow-y-auto" style="max-height: calc(90vh - 80px);">
                <h3 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Tambah Produk</h3>
                <form id="productForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="create_nama_produk" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                Produk</label>
                            <input type="text" name="nama_produk" id="create_nama_produk" placeholder="Nama Produk"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                        </div>
                        <div>
                            <label for="create_harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <input type="number" name="harga" id="create_harga" placeholder="Harga"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                        </div>
                        <div>
                            <label foberat" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                            <input type="number" name="stok" id="create_stok" placeholder="Stok"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                        </div>
                        <div>
                            <label for="create_berat" class="block text-sm font-medium text-gray-700 mb-2">Berat</label>
                            <input type="number" name="berat" id="create_berat" placeholder="Berat"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                        </div>
                        <div>
                            <label for="create_harga_b2b" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga B2B (opsional)
                            </label>
                            <input type="number" name="harga_b2b" id="create_harga_b2b"
                                placeholder="Harga khusus client"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                            <small class="mt-2 block text-xs text-gray-500">Harga Untuk list Price List Client</small>
                        </div>

                        <div>
                            <label for="create_status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="create_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label for="create_category_id"
                                class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" id="create_category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="create_brand_id"
                                class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                            <select name="brand_id" id="create_brand_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"
                                required>
                                <option value="">-- Pilih Brand --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="create_deskripsi"
                            class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="create_deskripsi" rows="4" placeholder="Deskripsi produk"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800"></textarea>
                    </div>

                    <div class="mb-6">
                        <label for="create_foto" class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                        <input type="file" name="foto" id="create_foto"
                            class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <small class="mt-2 block text-xs text-gray-500">Direkomendasikan ukuran minimal 1200x1200 piksel
                            untuk kualitas terbaik.</small>
                    </div>

                    <div class="mb-6">
                        <label for="create_images" class="block text-sm font-medium text-gray-700 mb-2">Gambar
                            Tambahan</label>
                        <input type="file" name="images[]" multiple id="create_images"
                            class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <small class="mt-2 block text-xs text-gray-500">Direkomendasikan ukuran minimal 1200x1200 piksel
                            untuk kualitas terbaik.</small>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi Produk</label>
                        <div id="spesifikasi-list-create" class="space-y-3">
                            <div class="flex gap-3 items-center">
                                <input type="text" name="specifications[0][key]"
                                    placeholder="Nama Spesifikasi (cth: RAM)"
                                    class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                                <input type="text" name="specifications[0][value]"
                                    placeholder="Nilai Spesifikasi (cth: 8GB)"
                                    class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                                <button type="button" onclick="this.closest('.flex').remove()"
                                    class="text-red-600 hover:text-red-800 text-xl transition duration-150 ease-in-out">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addSpec('create')"
                            class="flex items-center text-blue-600 mt-3 text-sm hover:underline transition duration-150 ease-in-out">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Spesifikasi
                        </button>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium transition duration-200 ease-in-out">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200 ease-in-out">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="deleteConfirm"
        class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-start justify-center pt-24 z-50 transition-opacity duration-300 ease-out opacity-0">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm transform -translate-y-4 scale-95 transition-all duration-300 ease-out">
            <h3 class="text-2xl font-bold mb-4 text-gray-800 text-center">Konfirmasi Hapus</h3>
            <p class="mb-6 text-gray-700 text-center">Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat
                dibatalkan.</p>
            <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteConfirm()"
                    class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium transition duration-200 ease-in-out">Batal</button>
                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition duration-200 ease-in-out">Hapus</button>
            </form>
        </div>
    </div>

    <script>
        let globalSpecCount = 0; // Menggunakan variabel global untuk spesifikasi di modal create

        // Fungsi pembuka/penutup modal dengan animasi
        function openModalWithAnimation(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return; // Exit if modal not found
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Pastikan flex agar centering bekerja
            // Trigger reflow to ensure transitions apply
            modal.offsetWidth;
            modal.classList.add('opacity-100');
            if (modal.querySelector('div')) {
                modal.querySelector('div').classList.remove('-translate-y-4', 'scale-95');
                modal.querySelector('div').classList.add('translate-y-0', 'scale-100');
            }
            document.body.classList.add('overflow-hidden');
        }

        function closeModalWithAnimation(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return; // Exit if modal not found
            modal.classList.remove('opacity-100');
            if (modal.querySelector('div')) {
                modal.querySelector('div').classList.remove('translate-y-0', 'scale-100');
                modal.querySelector('div').classList.add('-translate-y-4', 'scale-95');
            }
            modal.addEventListener('transitionend', function handler() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.removeEventListener('transitionend', handler);
                document.body.classList.remove('overflow-hidden');
            }, {
                once: true
            });
        }

        // Mengganti fungsi openModal dan closeModal yang ada
        function openModal(mode, product = null) {
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const title = document.getElementById('modalTitle');
            const spesifikasiListCreate = document.getElementById('spesifikasi-list-create');

            // Reset form and remove PUT method if exists
            form.reset();
            const existingMethodInput = document.getElementById('_method');
            if (existingMethodInput) {
                existingMethodInput.remove();
            }

            // Clear existing specifications in create modal
            spesifikasiListCreate.innerHTML = `
            <div class="flex gap-3 items-center">
                <input type="text" name="specifications[0][key]" placeholder="Nama Spesifikasi (cth: RAM)"
                    class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <input type="text" name="specifications[0][value]" placeholder="Nilai Spesifikasi (cth: 8GB)"
                    class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <button type="button" onclick="this.closest('.flex').remove()" class="text-red-600 hover:text-red-800 text-xl transition duration-150 ease-in-out">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        `;
            globalSpecCount = 1; // Reset count for new products

            if (mode === 'create') {
                form.action = '{{ route('produk.store') }}';
                title.textContent = 'Tambah Produk';
            } else if (mode === 'edit' && product) {
                form.action = `/produk/${product.id}`; // Sesuaikan dengan route Anda
                title.textContent = 'Edit Produk';
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                methodInput.id = '_method';
                form.appendChild(methodInput);

                // Populate form fields
                document.getElementById('create_nama_produk').value = product.nama_produk;
                document.getElementById('create_harga').value = product.harga;
                document.getElementById('create_stok').value = product.stok;
                document.getElementById('create_berat').value = product.berat;
                document.getElementById('create_status').value = product.status;
                document.getElementById('create_category_id').value = product.category_id;
                document.getElementById('create_brand_id').value = product.brand_id;
                document.getElementById('create_deskripsi').value = product.deskripsi;
            }

            openModalWithAnimation('productModal');
        }

        function closeModal() {
            closeModalWithAnimation('productModal');
        }

        function confirmDelete(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/produk/${id}`; // Sesuaikan dengan route Anda
            openModalWithAnimation('deleteConfirm');
        }

        function closeDeleteConfirm() {
            closeModalWithAnimation('deleteConfirm');
        }

        // Fungsi untuk modal detail produk
        function closeModalDetail(id) {
            closeModalWithAnimation(id);
        }

        // Fungsi untuk modal edit produk (dari foreach loop)
        function closeModalEdit(id) {
            closeModalWithAnimation(id);
        }


        // Fungsi tambah spesifikasi untuk modal CREATE
        function addSpec(mode) {
            let container;
            let index;
            if (mode === 'create') {
                container = document.getElementById('spesifikasi-list-create');
                index = globalSpecCount++;
            } else { // For specific edit modals
                container = document.getElementById('specifications-' + mode); // mode here is product.id
                index = container.querySelectorAll('div.flex').length; // Get current count of spec rows
            }

            const html = `
            <div class="flex gap-3 items-center mt-3">
                <input type="text" name="specifications[${index}][key]" placeholder="Nama Spesifikasi" class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <input type="text" name="specifications[${index}][value]" placeholder="Nilai Spesifikasi" class="border border-gray-300 p-2 rounded-lg w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <button type="button" onclick="this.closest('.flex').remove()" class="text-red-600 hover:text-red-800 text-xl transition duration-150 ease-in-out">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Event listener untuk membuka modal detail/edit yang spesifik (dari data-modal-target)
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-modal-target]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-modal-target').substring(
                        1); // Remove '#'
                    openModalWithAnimation(targetId);
                });
            });

            // Close modals when clicking outside
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModalWithAnimation(modal.id);
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
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
            /* 'forwards' keeps the end state */
        }
    </style>
@endsection
