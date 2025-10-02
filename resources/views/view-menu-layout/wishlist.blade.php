@extends('Layouts.main')

@section('judul', 'Wishlist | AGA IT COMPUTER')

@section('content')
    <section class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl md:text-3xl font-extrabold mb-6 inline-block border-b-2 border-green-500 pb-1 text-gray-800">
            Produk Favorit
        </h2>

        @if ($wishlists->isEmpty())
            <div class="flex flex-col items-center justify-center py-10 sm:py-20 bg-white rounded-lg shadow-md">
                <img src="/image/whislist.png" alt="Wishlist Kosong" class="w-40 sm:w-64 h-auto mx-auto mb-6 opacity-75">
                <h3 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">Wishlist-mu masih kosong</h3>
                <p class="text-sm sm:text-base text-gray-500 mb-6 text-center px-2">
                    Tambahkan produk favoritmu dan beli nanti saat dibutuhkan.
                </p>
                <a href="{{ url('/shop') }}"
                    class="bg-blue-600 text-white font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    Lihat Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- List Produk -->
                <div class="lg:col-span-2 space-y-4 max-h-[70vh] overflow-y-auto pr-0 lg:pr-2 custom-scrollbar"
                    id="wishlist-container">
                    <div
                        class="bg-gray-100 p-3 sm:p-4 rounded-lg flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3 sticky top-0 z-10 shadow-sm">
                        <input type="checkbox" id="select-all" class="w-5 h-5 accent-blue-600 cursor-pointer">
                        <label for="select-all" class="text-xs sm:text-sm font-semibold text-gray-700 select-none">
                            Pilih Semua Produk ({{ $wishlists->count() }})
                        </label>
                        <div class="ml-auto flex gap-2">
                            <button id="btn-add-selected"
                                class="bg-green-500 text-white font-medium px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs hover:bg-green-600 transition-colors hidden group-data-[selected=true]:inline-flex items-center gap-1">
                                + Keranjang
                            </button>
                            <button id="btn-delete-selected"
                                class="bg-red-500 text-white font-medium px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs hover:bg-red-600 transition-colors hidden group-data-[selected=true]:inline-flex items-center gap-1">
                                Hapus
                            </button>
                        </div>
                    </div>

                    @foreach ($wishlists as $item)
                        @php $product = $item->product; @endphp
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 p-3 sm:p-4 border rounded-lg shadow-sm bg-white wishlist-item"
                            data-id="{{ $item->id }}">
                            <input type="checkbox" class="wishlist-checkbox w-5 h-5 accent-blue-600 cursor-pointer">
                            <div class="w-full sm:w-24 h-32 sm:h-24 bg-gray-100 rounded-md overflow-hidden flex-shrink-0">
                                @if ($product && $product->foto)
                                    <img src="{{ asset(Storage::url($product->foto)) }}" alt="{{ $product->nama_produk }}"
                                        class="object-cover w-full h-full">
                                @else
                                    <img src="/image/agaicon.jpg" alt="No image" class="object-cover w-full h-full">
                                @endif
                            </div>
                            <div class="flex-1 space-y-1">
                                <h3 class="font-bold text-sm sm:text-base text-gray-800">
                                    {{ $product ? $product->nama_produk : 'Produk tidak ditemukan' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 font-semibold">
                                    @if ($product && $product->harga)
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
                                            <span class="text-sm text-gray-600 font-semibold">
                                                Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                            </span>
                                            <span class="ml-1 text-xs text-red-500">
                                                Diskon :
                                                ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-600 font-semibold">
                                                Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                                <button
                                    class="add-to-cart-single bg-yellow-500 text-white font-medium px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs hover:bg-yellow-600 transition-colors flex items-center gap-1 w-full sm:w-auto"
                                    data-id="{{ $item->id }}">
                                    + Keranjang
                                </button>
                                <button
                                    class="delete-single text-red-500 hover:text-red-700 text-xs sm:text-sm font-semibold transition-colors w-full sm:w-auto text-center"
                                    data-id="{{ $item->id }}">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Ringkasan -->
                <div class="border p-4 sm:p-6 rounded-lg bg-white shadow-md h-fit lg:col-span-1 sticky top-20">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Ringkasan Wishlist</h3>
                    <div class="flex justify-between items-center mb-1">
                        <p class="text-sm text-gray-600">Total Produk</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $wishlists->count() }}</p>
                    </div>
                    <hr class="my-3 sm:my-4 border-gray-200">
                    <p class="text-xs text-gray-400 mb-4">
                        *Produk yang dipilih dari wishlist akan ditambahkan ke keranjang belanja Anda.
                    </p>
                    <a href="{{ url('/shop') }}"
                        class="block w-full bg-blue-600 text-white text-center py-2 sm:py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-md">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        @endif
    </section>

    <div id="confirmation-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden z-[999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm transform transition-all scale-95 duration-300">
            <div class="text-center">
                <svg id="modal-icon" class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.5-1.637 1.732-2.924l-6.928-12.012a2 2 0 00-3.464 0L3.31 16.076C2.54 17.363 3.5 19 5.04 19z">
                    </path>
                </svg>
                <h3 id="modal-title" class="mt-4 text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p id="modal-text" class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus produk ini dari
                    wishlist?</p>
            </div>
            <div class="mt-5 sm:mt-6 flex justify-end gap-3">
                <button type="button"
                    class="close-modal-btn w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
                <button type="button" id="modal-confirm-btn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <div id="notification-container" class="fixed bottom-5 right-5 z-[1000] space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metaCsrf = document.querySelector('meta[name="csrf-token"]');
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.wishlist-checkbox');
            const btnAddSelected = document.getElementById('btn-add-selected');
            const btnDeleteSelected = document.getElementById('btn-delete-selected');
            const confirmModal = document.getElementById('confirmation-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalText = document.getElementById('modal-text');
            const modalIcon = document.getElementById('modal-icon');
            const modalConfirmBtn = document.getElementById('modal-confirm-btn');
            const notificationContainer = document.getElementById('notification-container');

            // 🔔 Notifikasi Toast
            function showNotification(message, type = 'success') {
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500'
                };
                const toast = document.createElement('div');
                toast.className =
                    `${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg transition-transform duration-300 transform translate-x-full`;
                toast.textContent = message;
                notificationContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                    toast.classList.add('translate-x-0');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('translate-x-0');
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Ambil semua ID yang dipilih
            function getSelectedIds() {
                const checked = document.querySelectorAll('.wishlist-checkbox:checked');
                return Array.from(checked).map(cb => cb.closest('.wishlist-item').dataset.id);
            }

            // Helper post JSON
            async function postJson(url, data) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': metaCsrf.content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            }

            // Tampilkan/hidden tombol bulk
            function updateBulkButtons() {
                const selectedCount = getSelectedIds().length;
                if (selectedCount > 0) {
                    btnAddSelected.classList.remove('hidden');
                    btnDeleteSelected.classList.remove('hidden');
                } else {
                    btnAddSelected.classList.add('hidden');
                    btnDeleteSelected.classList.add('hidden');
                }
            }

            // ✅ Handler hasil addToCart dari controller
            function handleAddToCartResponse(res) {
                if (res.hasil) {
                    Object.values(res.hasil).forEach(item => {
                        const tipe = (['gagal', 'stok_habis', 'error'].includes(item.status)) ? 'error' :
                            'success';
                        showNotification(`${item.produk}: ${item.message}`, tipe);
                    });
                } else {
                    showNotification(res.message || 'Produk diproses.', 'info');
                }
            }

            // Select all
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkButtons();
                });
            }
            checkboxes.forEach(cb => cb.addEventListener('change', updateBulkButtons));

            // Single hapus
            document.querySelectorAll('.delete-single').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalTitle.textContent = 'Konfirmasi Hapus';
                    modalText.textContent =
                        'Apakah Anda yakin ingin menghapus produk ini dari wishlist?';
                    modalIcon.classList.remove('text-green-500', 'text-blue-500');
                    modalIcon.classList.add('text-red-500');
                    modalConfirmBtn.textContent = 'Hapus';
                    confirmModal.dataset.action = 'deleteSingle';
                    confirmModal.dataset.itemId = this.dataset.id;
                    confirmModal.classList.remove('hidden');
                });
            });

            // Bulk hapus
            if (btnDeleteSelected) {
                btnDeleteSelected.addEventListener('click', function() {
                    modalTitle.textContent = 'Konfirmasi Hapus Item';
                    modalText.textContent =
                        `Apakah Anda yakin ingin menghapus ${getSelectedIds().length} produk yang dipilih?`;
                    modalIcon.classList.remove('text-green-500', 'text-blue-500');
                    modalIcon.classList.add('text-red-500');
                    modalConfirmBtn.textContent = 'Hapus';
                    confirmModal.dataset.action = 'deleteBulk';
                    confirmModal.classList.remove('hidden');
                });
            }

            // Single tambah keranjang
            document.querySelectorAll('.add-to-cart-single').forEach(btn => {
                btn.addEventListener('click', async function() {
                    try {
                        const res = await postJson("{{ route('wishlist.addToCart') }}", {
                            ids: [this.dataset.id]
                        });
                        handleAddToCartResponse(res);
                    } catch (err) {
                        showNotification('Gagal menambahkan ke keranjang: ' + err.message,
                            'error');
                    }
                });
            });

            // Bulk tambah keranjang
            if (btnAddSelected) {
                btnAddSelected.addEventListener('click', async function() {
                    try {
                        const ids = getSelectedIds();
                        const res = await postJson("{{ route('wishlist.addToCart') }}", {
                            ids
                        });
                        handleAddToCartResponse(res);
                    } catch (err) {
                        showNotification('Gagal menambahkan ke keranjang: ' + err.message, 'error');
                    }
                });
            }

            // Close modal
            document.querySelectorAll('.close-modal-btn').forEach(btn => {
                btn.addEventListener('click', () => confirmModal.classList.add('hidden'));
            });

            // Confirm modal
            modalConfirmBtn.addEventListener('click', async function() {
                const action = confirmModal.dataset.action;
                let ids = [];
                if (action === 'deleteSingle') {
                    ids = [confirmModal.dataset.itemId];
                } else if (action === 'deleteBulk') {
                    ids = getSelectedIds();
                }

                try {
                    const res = await postJson("{{ route('wishlist.deleteSelected') }}", {
                        ids
                    });
                    showNotification(res.message || 'Produk berhasil dihapus', 'success');
                    ids.forEach(id => {
                        const el = document.querySelector(`.wishlist-item[data-id="${id}"]`);
                        if (el) el.remove();
                    });
                    updateBulkButtons();
                } catch (err) {
                    showNotification('Gagal menghapus item: ' + err.message, 'error');
                } finally {
                    confirmModal.classList.add('hidden');
                }
            });
        });
    </script>


    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    {{-- Rekomendasi Produk & Produk Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-end mb-6 border-b-2 border-green-500 pb-2">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Rekomendasi Untukmu 💖</h2>
            <a href="/shop"
                class="text-green-600 font-semibold text-xs sm:text-sm hover:underline hover:text-green-700 transition">Lihat
                Semua →</a>
        </div>

        <div class="relative group">
            <div id="rekomendasiSlider"
                class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 sm:gap-6 pb-2 no-scrollbar px-1">
                @foreach ($rekomendasiProduk as $produk)
                    <div
                        class="min-w-[220px] sm:min-w-[260px] md:min-w-[300px] lg:min-w-[340px] xl:min-w-[380px] flex-shrink-0 snap-start bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}" class="block">
                            <img src="{{ $produk->foto ? asset(Storage::url($produk->foto)) : '/image/default-product.png' }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-full h-32 sm:h-40 object-cover rounded-t-xl transition-transform duration-500 hover:scale-110">
                        </a>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-sm sm:text-base font-bold line-clamp-2 text-gray-800 mb-1">
                                {{ $produk->nama_produk }}</h3>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-yellow-400 text-xs sm:text-sm">★
                                    {{ number_format($produk->rating ?? 0, 1) }}</div>
                                <div class="text-gray-500 text-[10px] sm:text-xs">({{ $produk->ulasan_count ?? 0 }})</div>
                            </div>
                            <p class="text-[10px] sm:text-xs text-gray-600">Terjual {{ (int) ($produk->terjual ?? 0) }}
                            </p>
                            <div class="mt-2 sm:mt-3 flex items-center justify-between gap-2">
                                <div class="text-base sm:text-xl font-extrabold text-green-700">
                                    @php
                                        $discount = $produk->discounts
                                            ->filter(function ($d) {
                                                return $d->type === 'product' &&
                                                    $d->status == 1 &&
                                                    (is_null($d->expired_at) || $d->expired_at >= now());
                                            })
                                            ->first();

                                        $hargaAsli = $produk->harga;
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
                                        <span class="text-xl font-extrabold text-green-700">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-1 text-xs text-red-500">
                                            Diskon :
                                            ({{ $discount->discount_type === 'percent' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.') }})
                                        </span>
                                    @else
                                        <span class="text-xl font-extrabold text-green-700">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('produk.showdetail', $produk->slug ?? '#') }}"
                                    class="text-white bg-green-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-green-700 transition-colors">Lihat</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <script>
        // slider controls (aman jika elemen ada)
        window.geserKiri = function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.scrollBy({
                left: -220,
                behavior: 'smooth'
            });
        };
        window.geserKanan = function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.scrollBy({
                left: 220,
                behavior: 'smooth'
            });
        };
    </script>

@endsection
