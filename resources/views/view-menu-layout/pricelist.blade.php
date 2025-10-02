@extends('Layouts.main')
@section('judul', 'AGA IT COMPUTER | PRICE LIST ')
@section('content')
    <br>
    <div class="overflow-x-auto px-2 sm:px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Judul Halaman -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-wide">
                    📋 Price List
                </h2>
                <p class="text-gray-500 text-sm md:text-base mt-1">
                    Temukan berbagai produk lengkap beserta harga dan stok kami
                </p>
            </div>



            <form action="{{ route('price-list') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg shadow-inner">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Input Pencarian --}}
                    <div class="col-span-1 md:col-span-1">
                        <label for="search" class="sr-only">Cari produk...</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" placeholder="Cari produk..."
                                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Kategori --}}
                    <div class="col-span-1 md:col-span-1">
                        <label for="category" class="sr-only">Filter Kategori</label>
                        <select name="category" id="category"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}"
                                    {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Opsi Pengurutan --}}
                    <div class="col-span-1 md:col-span-1">
                        <label for="sort" class="sr-only">Urutkan Berdasarkan</label>
                        <select name="sort" id="sort"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Urutkan Berdasarkan</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Produk (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Produk
                                (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga
                                Termahal</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                        Tampilkan Hasil
                    </button>
                    <a href="{{ route('price-list') }}"
                        class="px-6 py-2.5 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition duration-300 ml-2">
                        Reset
                    </a>
                    {{-- Tombol Export Excel --}}
                    <a href="{{ route('price-list.export', request()->query()) }}"
                        class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-300 ml-2">
                        Export Excel
                    </a>
                </div>
            </form>

            <!-- Tabel Produk -->
            <table class="w-full table-auto border-separate border-spacing-y-3 text-sm md:text-bas  e">
                <thead class="bg-gray-100 rounded-lg">
                    <tr>
                        <th class="px-2 md:px-4 py-3 text-left font-semibold text-gray-700 rounded-l-lg">Produk</th>
                        <th class="px-2 md:px-4 py-3 text-left font-semibold text-gray-700">Harga</th>
                        <th class="px-2 md:px-4 py-3 text-left font-semibold text-gray-700">Stok</th>
                        <th class="px-2 md:px-4 py-3 text-center font-semibold text-gray-700 rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr
                            class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition duration-300 hover:-translate-y-1">
                            <td class="px-2 md:px-4 py-4 rounded-l-lg">
                                <div class="flex items-start md:items-center gap-3 md:gap-4">
                                    <div>
                                        <div class="font-medium text-gray-900 line-clamp-1">
                                            {{ $product->nama_produk }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 md:px-4 py-4 text-green-600 font-bold text-base md:text-lg">
                                Rp {{ number_format($product->priceList->price ?? $product->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-2 md:px-4 py-4">
                                <span
                                    class="px-2 md:px-3 py-1 inline-flex text-xs md:text-sm leading-5 font-semibold rounded-full
                                @if ($product->stok > 10) bg-green-100 text-green-800
                                @elseif($product->stok > 0) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ $product->stok > 0 ? $product->stok . ' Tersedia' : 'Habis' }}
                                </span>
                            </td>
                            <td class="px-2 md:px-4 py-4 text-center rounded-r-lg">
                                <div class="flex justify-center gap-2 md:gap-3">
                                    <!-- Tombol Tambah ke Keranjang -->
                                    <button type="button" data-product-id="{{ $product->id }}"
                                        class="btn-add-cart p-2 md:p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                        title="Tambah ke Keranjang">
                                        <i class="fas fa-shopping-cart text-sm md:text-lg"></i>
                                    </button>

                                    <!-- Tombol Detail Produk -->
                                    <button type="button" onclick="openDetailModal({{ $product->id }})"
                                        class="p-2 md:p-3 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50"
                                        title="Detail Produk">
                                        <i class="fas fa-info-circle text-sm md:text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




    {{-- Modal Detail Produk (langsung di Blade untuk tiap produk) --}}
    @foreach ($products as $product)
        <div id="detailModal-{{ $product->id }}"
            class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 p-2 sm:p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md md:max-w-2xl relative overflow-y-auto max-h-[90vh] p-4 sm:p-6 md:p-8 transform transition-all scale-95 duration-300 ease-out">

                {{-- Tombol Close --}}
                <button onclick="closeDetailModal({{ $product->id }})"
                    class="absolute top-4 right-4 text-gray-500 hover:text-red-500 transition-colors duration-200">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Header --}}
                <div class="flex items-center gap-6 mb-6">
                    @if ($product->foto)
                        <img src="{{ Storage::url($product->foto) }}" alt="{{ $product->nama_produk }}"
                            class="w-24 h-24 object-cover rounded-xl shadow-lg">
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->nama_produk }}</h2>
                        <p class="text-xl font-extrabold text-green-600 mt-1">
                            @if ($product->priceList?->price)
                                Rp {{ number_format($product->priceList->price, 0, ',', '.') }}
                            @elseif ($product->harga)
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            @else
                                <span class="text-red-500 italic">Hubungi Admin untuk harga</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Info Tambahan --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="font-semibold">{{ $product->category->nama_kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Brand</p>
                        <p class="font-semibold">{{ $product->brand->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Berat</p>
                        <p class="font-semibold">{{ $product->berat ? $product->berat . ' gram' : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stok</p>
                        <p class="font-semibold">{{ $product->stok ?? '-' }}</p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Deskripsi Produk</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $product->deskripsi ?? 'Tidak ada deskripsi produk.' }}
                    </p>
                </div>

                {{-- Spesifikasi --}}
                <div class="mt-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Spesifikasi</h3>
                    @if ($product->specifications && count($product->specifications) > 0)
                        <ul class="list-disc ml-6 space-y-2">
                            @foreach ($product->specifications as $spec)
                                <li><span class="font-semibold text-gray-800">{{ $spec->key }}</span>:
                                    {{ $spec->value }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Tidak ada spesifikasi tambahan</p>
                    @endif
                </div>

                {{-- Galeri --}}
                @if ($product->images && count($product->images) > 0)
                    <div class="mt-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-2">Galeri Foto</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                            @foreach ($product->images as $img)
                                <img src="{{ Storage::url($img->image_path) }}"
                                    class="w-full h-32 md:h-40 object-cover rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endforeach


    <script>
        function showToast(message, type = 'info', duration = 2000) {
            const toast = document.createElement('div');
            toast.textContent = message;
            toast.className = `fixed bottom-5 right-5 px-4 py-2 rounded-lg text-white shadow-lg z-[9999] transition
            ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'}`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        document.addEventListener("DOMContentLoaded", () => {
            // pakai items() supaya array, bukan paginator object
            window.products = @json($products->items());

            const detailModal = document.getElementById('detailModal');
            const modalContent = document.getElementById('modalContent');

            // Tambah ke Keranjang
            document.querySelectorAll('.btn-add-cart').forEach(button => {
                button.addEventListener('click', async function() {
                    const productSlug = this.dataset.productSlug || null;
                    const productId = this.dataset.productId || null;
                    const qty = 1;

                    showToast('Menambahkan ke keranjang...', 'info', 900);

                    try {
                        const res = await fetch('{{ route('shop.addToCart') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                product_id: productSlug || productId,
                                jumlah: qty,
                                source: "pricelist"
                            }),

                        });

                        if (!res.ok) {
                            if (res.status === 401) {
                                showLoginModal();
                                return;
                            }

                            const err = await res.json().catch(() => ({}));
                            showToast(err.error || 'Gagal menambahkan ke keranjang', 'error');
                            return;
                        }

                        const data = await res.json();
                        if (data.status === 'ok') {
                            showToast(
                                `Ditambahkan ke keranjang, cek icon keranjang (total: ${data.jumlah || qty})`,
                                'success'
                            );
                        }
                    } catch (e) {
                        showToast('Terjadi kesalahan', 'error');
                    }
                });
            });



            window.openDetailModal = function(id) {
                let modal = document.getElementById(`detailModal-${id}`);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }

            window.closeDetailModal = function(id) {
                let modal = document.getElementById(`detailModal-${id}`);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

        });
    </script>
@endsection
