@extends('Layouts.main')

@section('judul', 'AGA IT COMPUTER | Keranjang')

@section('content')

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2
            class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-6 inline-block border-b-2 border-green-500 pb-1 text-gray-800">
            Keranjang Belanja
        </h2>

        @if ($carts->isEmpty())
            <div class="flex flex-col items-center justify-center py-10 sm:py-20 bg-white rounded-lg shadow-md text-center">
                <img src="/image/keranjang.png" alt="Keranjang Kosong" class="w-40 sm:w-64 h-auto mx-auto mb-6 opacity-75">
                <h3 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">Wah, Keranjang belanjamu kosong!</h3>
                <p class="text-sm sm:text-base text-gray-500 mb-6">Yuk, mulai belanja sekarang dan temukan produk terbaik
                    untukmu.</p>
                <a href="{{ url('/shop') }}"
                    class="bg-blue-600 text-white font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Cart List -->
                <div class="md:col-span-2">
                    <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-3 sticky top-0 z-10 shadow-sm">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" class="form-checkbox text-green-600 w-5 h-5 mr-2 accent-green-600"
                                id="select-all">
                            <span class="text-sm font-semibold text-gray-700">Pilih Semua</span>
                        </label>
                        <div class="ml-auto flex gap-2" id="bulk-action-buttons">
                            <button id="btn-delete-selected"
                                class="bg-red-500 text-white font-medium px-4 py-2 rounded-full text-xs hover:bg-red-600 transition-colors hidden group-data-[selected=true]:inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.013 21H7.987a2 2 0 01-1.92-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-4 max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar" id="cart-list">
                        @foreach ($carts as $item)
                            @php $product = $item->product; @endphp
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 border rounded-lg shadow-sm bg-white cart-item"
                                data-id="{{ $item->id }}" data-price="{{ $product->harga ?? 0 }}">

                                <!-- Checkbox + Gambar -->
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    <input type="checkbox" class="form-checkbox w-5 h-5 accent-green-600 checkbox-item"
                                        value="{{ $item->id }}">
                                    <div
                                        class="w-24 h-24 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 mx-auto sm:mx-0">
                                        <img src="{{ $product && $product->foto ? asset(Storage::url($product->foto)) : '/image/agaicon.jpg' }}"
                                            alt="{{ $product ? $product->nama_produk : 'No image' }}"
                                            class="object-cover w-full h-full rounded">
                                    </div>
                                </div>

                                <!-- Detail Produk -->
                                <div class="flex-1 space-y-2 w-full">
                                    <h3 class="font-bold text-base sm:text-lg text-gray-800">
                                        {{ $product ? $product->nama_produk : 'Produk tidak ditemukan' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 font-semibold">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        @if ($item->source === 'pricelist')
                                            <span class="ml-1 text-xs text-blue-500">(Price List)</span>
                                        @endif
                                    </p>

                                    {{-- <!-- Catatan -->
                                    <label class="block text-xs text-gray-600">
                                        Catatan:
                                        <input type="text" class="border mt-1 p-1 w-full rounded text-sm note-input"
                                            data-id="{{ $item->id }}" value="{{ $item->note ?? '' }}"
                                            placeholder="Contoh: warna hitam">
                                    </label> --}}

                                    <!-- Qty -->
                                    <div class="flex items-center gap-2 mt-2">
                                        <button
                                            class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full decrement-btn">-</button>
                                        <input type="number" min="1" value="{{ $item->jumlah }}"
                                            data-id="{{ $item->id }}"
                                            class="w-14 border rounded text-sm text-center py-1 jumlah-input">
                                        <button
                                            class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full increment-btn">+</button>
                                    </div>
                                </div>

                                <!-- Subtotal + Hapus -->
                                <div class="flex flex-col items-end gap-2 text-right w-full sm:w-auto mt-3 sm:mt-0">
                                    <strong class="text-base sm:text-lg text-green-600 item-subtotal">
                                        Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                    </strong>
                                    <button
                                        class="delete-single text-red-500 hover:text-red-700 text-sm font-semibold transition-colors"
                                        data-id="{{ $item->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Summary -->
                <div class="border p-4 sm:p-6 rounded-lg bg-white shadow-md h-fit md:col-span-1 sticky top-20">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Ringkasan Belanja</h3>
                    <div class="flex justify-between items-center mb-1">
                        <p class="text-sm text-gray-600">Total Item</p>
                        <p class="text-sm font-semibold text-gray-800" id="total-items">{{ $carts->sum('jumlah') }}</p>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <p class="text-sm text-gray-600">Subtotal</p>
                        <p class="text-sm font-semibold text-gray-800" id="total-price">
                            Rp{{ number_format(
                                $carts->reduce(function ($carry, $c) {
                                    return $carry + $c->harga * $c->jumlah;
                                }, 0),
                                0,
                                ',',
                                '.',
                            ) }}
                        </p>
                    </div>

                    <hr class="my-4 border-gray-200">
                    <button id="btn-checkout-selected"
                        class="w-full bg-green-600 text-white font-medium px-6 py-3 rounded-lg text-sm hover:bg-green-700 transition-colors hidden group-data-[selected=true]:inline-flex items-center justify-center gap-2 mt-2">
                        Checkout </button>
                </div>
            </div>
        @endif
    </section>

    <!-- Universal Confirmation Modal -->
    <div id="confirmation-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden z-[999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm transform transition-all scale-95 duration-300">
            <div class="text-center">
                <svg id="modal-icon" class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M3 16c.5 1.5 1 3 5 3h8c4 0 4-1.5 4-3V7H3v9z" />
                </svg>
                <h3 id="modal-title" class="mt-4 text-lg font-semibold text-gray-900">Konfirmasi</h3>
                <p id="modal-text" class="mt-2 text-sm text-gray-500">Apakah Anda yakin?</p>
            </div>
            <div class="mt-5 sm:mt-6 flex justify-end gap-3">
                <button type="button"
                    class="close-modal-btn w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                <button type="button" id="modal-confirm-btn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notif"
        class="hidden fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium z-50">
        <span id="notif-msg"></span>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden z-[1000] flex items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95 duration-300 overflow-hidden">
            <!-- Header -->
            <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Konfirmasi Checkout</h3>
                <button class="close-checkout-modal hover:text-gray-200">&times;</button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <p class="text-sm text-gray-600">Kamu akan melanjutkan checkout dengan ringkasan berikut:</p>
                <div class="flex justify-between">
                    <span class="text-gray-700 font-medium">Total Item</span>
                    <span id="checkout-total-items" class="font-semibold text-gray-900">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700 font-medium">Subtotal</span>
                    <span id="checkout-total-price" class="font-semibold text-green-600">Rp0</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button type="button"
                    class="close-checkout-modal px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Batal
                </button>
                <button type="button" id="checkout-confirm-btn"
                    class="px-4 py-2 rounded-md bg-green-600 text-sm font-semibold text-white hover:bg-green-700">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>


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
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const qs = sel => document.querySelector(sel);
            const $$ = sel => Array.from(document.querySelectorAll(sel));

            const selectAll = qs('#select-all');
            const checkboxes = $$('.checkbox-item');
            const bulkButtons = qs('#bulk-action-buttons');
            const btnDelete = qs('#btn-delete-selected');
            const btnCheckout = qs('#btn-checkout-selected');
            const modal = qs('#confirmation-modal');
            const modalTitle = qs('#modal-title');
            const modalText = qs('#modal-text');
            const modalConfirmBtn = qs('#modal-confirm-btn');
            const closeModalBtns = qs('.close-modal-btn');
            const notif = qs('#notif');
            const notifMsg = qs('#notif-msg');

            function numberWithDots(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            const formatCurrency = n => 'Rp' + numberWithDots(n);

            function updateSummary() {
                let totalItems = 0,
                    totalPrice = 0,
                    selectedCount = 0;

                $$('.cart-item').forEach(item => {
                    const checkbox = item.querySelector('.checkbox-item');
                    if (checkbox?.checked) {
                        selectedCount++;
                        const qty = parseInt(item.querySelector('.jumlah-input')?.value || 0, 10);
                        const subtotal = parseInt(item.querySelector('.item-subtotal')?.textContent.replace(
                            /[^\d]/g, '') || 0, 10);
                        totalItems += qty;
                        totalPrice += subtotal;
                    }
                });

                const totalItemsEl = qs('#total-items');
                const totalPriceEl = qs('#total-price');

                if (totalItemsEl) totalItemsEl.textContent = totalItems;
                if (totalPriceEl) totalPriceEl.textContent = formatCurrency(totalPrice);

                if (selectedCount > 0) {
                    btnDelete?.classList.remove('hidden');
                    btnCheckout?.classList.remove('hidden');
                } else {
                    btnDelete?.classList.add('hidden');
                    btnCheckout?.classList.add('hidden');
                }
            }


            function showNotif(msg, type = 'success') {
                notif.classList.remove("hidden", "bg-green-600", "bg-red-600");
                notif.classList.add(type === 'success' ? "bg-green-600" : "bg-red-600");
                notifMsg.textContent = msg;
                setTimeout(() => {
                    notif.classList.add('hidden');
                }, 3000);
            }

            function showModal(title, text, confirmCb) {
                modalTitle.textContent = title;
                modalText.textContent = text;
                modal.classList.remove('hidden');
                modalConfirmBtn.onclick = () => {
                    confirmCb();
                    modal.classList.add('hidden');
                };
                qs('.close-modal-btn').onclick = () => {
                    modal.classList.add('hidden');
                };
            }

            // Select all
            selectAll?.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateSummary();
            });
            checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

            // Delete selected
            btnDelete?.addEventListener('click', () => {
                const ids = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
                if (ids.length === 0) {
                    showNotif('Pilih item terlebih dahulu', 'error');
                    return;
                }
                showModal('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus item yang dipilih?',
                    async () => {
                        try {
                            const res = await fetch("{{ route('cart.deleteSelected') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({
                                    ids
                                })
                            });
                            const data = await res.json();
                            showNotif(data.message || 'Berhasil dihapus', 'success');
                            ids.forEach(id => qs(`.cart-item[data-id="${id}"]`)?.remove());
                            updateSummary();
                        } catch (err) {
                            // console.error(err);
                            showNotif('Gagal menghapus', 'error');
                        }
                    });
            });

            // Checkout selected
            btnCheckout?.addEventListener('click', () => {
                const ids = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
                if (ids.length === 0) {
                    showNotif('Pilih item terlebih dahulu', 'error');
                    return;
                }

                // Ambil data ringkasan dari DOM
                const totalItems = qs('#total-items').textContent;
                const totalPrice = qs('#total-price').textContent;

                // Isi modal checkout
                qs('#checkout-total-items').textContent = totalItems;
                qs('#checkout-total-price').textContent = totalPrice;
                qs('#checkout-modal').classList.remove('hidden');

                // Tutup modal
                $$('.close-checkout-modal').forEach(btn => btn.onclick = () => {
                    qs('#checkout-modal').classList.add('hidden');
                });

                // Konfirmasi checkout
                qs('#checkout-confirm-btn').onclick = async () => {
                    try {
                        const res = await fetch("{{ route('cart.checkoutSelected') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                ids
                            })
                        });
                        const data = await res.json();
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            showNotif(data.message || 'Stok Habis', 'success');
                        }
                    } catch (err) {
                        // console.error(err);
                        showNotif('Gagal checkout', 'error');
                    } finally {
                        qs('#checkout-modal').classList.add('hidden');
                    }
                };
            });

            // Delete single
            $$('.delete-single').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    showModal('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus item ini?',
                        async () => {
                            try {
                                const res = await fetch(
                                    "{{ route('cart.deleteSelected') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        },
                                        body: JSON.stringify({
                                            ids: [id]
                                        })
                                    });
                                const data = await res.json();
                                showNotif(data.message || 'Berhasil', 'success');
                                qs(`.cart-item[data-id="${id}"]`)?.remove();
                                updateSummary();
                            } catch (err) {
                                // console.error(err);
                                showNotif('Gagal menghapus', 'error');
                            }
                        });
                });
            });

            // Update qty
            $$('.jumlah-input').forEach(input => {
                input.dataset.oldValue = input.value;
                input.addEventListener('change', async function() {
                    const id = this.dataset.id;
                    const jumlah = Math.max(1, parseInt(this.value || 1, 10));
                    const old = parseInt(this.dataset.oldValue || 1, 10);
                    this.value = jumlah;
                    this.disabled = true;
                    try {
                        const res = await fetch("{{ route('cart.updateQty') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                id,
                                jumlah
                            })
                        });
                        const data = await res.json();
                        if (!res.ok || data.stockExceeded) {
                            this.value = old;
                            this.dataset.oldValue = old;
                            alert(data.available ?
                                `Stok tidak cukup. Tersisa ${data.available}` : data
                                .message || 'Jumlah melebihi stok');
                        } else {
                            this.dataset.oldValue = this.value;
                            if (data.subtotal) qs(`.cart-item[data-id="${id}"] .item-subtotal`)
                                .textContent = formatCurrency(data.subtotal);
                        }
                        updateSummary();
                    } catch (err) {
                        // console.error(err);
                        this.value = old;
                        this.dataset.oldValue = old;
                        alert('Gagal update jumlah');
                    } finally {
                        this.disabled = false;
                    }
                });
            });

            updateSummary();
        });
    </script>



    <script>
        // slider controls
        window.geserKiri = function(id) {
            const el = qs(`#${id}`);
            if (!el) return;
            el.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        };
        window.geserKanan = function(id) {
            const el = qs(`#${id}`);
            if (!el) return;
            el.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        };
    </script>

@endsection
