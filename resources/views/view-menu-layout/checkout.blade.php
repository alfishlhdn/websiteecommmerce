@extends('Layouts.main')
@section('judul', 'Checkout | AGA IT COMPUTER')
@section('content')

    <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}">
        @csrf
        {{-- <input type="hidden" id="checkoutSource" value="{{ session()->has('checkout_buy_now') ? 'buy_now' : 'cart' }}">
        @if (session()->has('checkout_buy_now'))
            <input type="hidden" id="buyNowPrice" value="{{ $cart->first()->product->harga }}">
            <input type="hidden" id="buyNowQty" value="{{ $cart->first()->jumlah }}">
        @endif --}}

        <input type="hidden" name="return_to" id="return_to" value="{{ request('return_to', url()->previous()) }}">
        <input type="hidden" id="voucherSubtotalId" name="voucher_subtotal_id" value="">
        <input type="hidden" id="voucherShippingId" name="voucher_shipping_id" value="">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            <!-- ================= Alamat & Produk ================= -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Alamat -->
                <div class="bg-white shadow rounded-xl p-5 sm:p-6">
                    <h2 class="text-gray-800 font-bold text-lg sm:text-xl mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i> Alamat Pengiriman
                    </h2>

                    <select id="existingAddress" class="w-full border rounded p-2 mb-4 text-sm sm:text-base">
                        <option value="">-- Pilih alamat tersimpan --</option>
                        @foreach ($addresses as $addr)
                            <option value="{{ $addr->id }}" data-label="{{ $addr->label }}"
                                data-telepon="{{ $addr->telepon }}" data-alamat="{{ $addr->alamat_lengkap }}"
                                data-kecamatan="{{ $addr->kecamatan }}" data-kota="{{ $addr->kota }}"
                                data-provinsi="{{ $addr->provinsi }}" data-kelurahan="{{ $addr->kelurahan }}">
                                {{ $addr->label }} - {{ $addr->alamat_lengkap }}, {{ $addr->kota }}
                            </option>
                        @endforeach
                    </select>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input name="label" id="label" placeholder="Label (Rumah/Kantor)"
                            class="border rounded p-2 text-sm sm:text-base" />
                        <input name="telepon" id="telepon" placeholder="Telepon"
                            class="border rounded p-2 text-sm sm:text-base" />
                    </div>

                    <textarea name="alamat_lengkap" id="alamat_lengkap" placeholder="Alamat lengkap"
                        class="w-full border rounded p-2 text-sm sm:text-base mt-3" rows="3"></textarea>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                        <select id="provinsi" class="border rounded p-2 text-sm sm:text-base"></select>
                        <select id="kota" class="border rounded p-2 text-sm sm:text-base"></select>
                        <select id="kecamatan" class="border rounded p-2 text-sm sm:text-base"></select>
                        <select id="kelurahan" class="border rounded p-2 text-sm sm:text-base"></select>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-4">
                        <button type="button" id="saveAddress"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Simpan alamat
                        </button>
                        <button type="button" id="useAddress"
                            class="w-full sm:w-auto px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            Gunakan alamat ini
                        </button>
                    </div>

                    <input type="hidden" name="user_address_id" id="user_address_id" />
                </div>

                <!-- Produk -->
                <div class="bg-white shadow rounded-xl p-5 sm:p-6">
                    <h2 class="text-gray-800 font-bold text-lg sm:text-xl mb-4">Produk Dipesan</h2>
                    <div class="divide-y">
                        @foreach ($cart as $item)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center py-3 gap-3 cart-item"
                                data-id="{{ $item->id }}">
                                <img src="{{ asset(Storage::url($item->product->foto)) }}"
                                    class="w-16 h-16 rounded-md object-cover">
                                <div class="flex-1">
                                    <p class="font-medium text-sm sm:text-base">{{ $item->product->nama_produk }}</p>
                                    <p class="text-xs sm:text-sm text-gray-500">x{{ $item->jumlah }}</p>
                                    @auth
                                        @if (in_array(Auth::user()->role, ['client', 'admin', 'superadmin']))
                                            @if ($item->source === 'shop')
                                                <span class="text-[10px] text-blue-500 font-medium">Dari Shop</span>
                                            @elseif($item->source === 'pricelist')
                                                <span class="text-[10px] text-purple-500 font-medium">Dari Pricelist</span>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                                <p class="font-semibold text-green-600 text-sm sm:text-base">
                                    Rp
                                    {{ number_format(($item->harga ?? $item->product->harga) * $item->jumlah, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pilih kurir -->
                    <div class="mt-4">
                        <label class="block mb-1 font-semibold text-sm sm:text-base">Kurir Pengiriman</label>
                        <select name="kurir_id" id="kurir_id" class="w-full border rounded p-2 text-sm sm:text-base">
                            @foreach ($kurir as $k)
                                @php
                                    $beratKg = ceil($totalBerat / 1000);
                                    $finalPrice = $k->price * $beratKg;
                                @endphp
                                <option value="{{ $k->id }}" data-price="{{ $k->price }}"
                                    data-ket="{{ strtolower($k->keterangan) }}">
                                    {{ $k->name }} - {{ $k->service_code }} ({{ $k->service_type }}) -
                                    {{ $k->keterangan }} - Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <textarea name="catatan" id="catatan" placeholder="Catatan (opsional)"
                        class="w-full border rounded p-2 text-sm sm:text-base mt-4" rows="3"></textarea>
                </div>
            </div>

            <!-- ================= Ringkasan & Pembayaran ================= -->
            <div class="space-y-6">
                <div class="bg-white shadow rounded-xl p-5 sm:p-6">
                    <h2 class="font-bold text-gray-800 text-lg sm:text-xl mb-3">Ringkasan Transaksi</h2>
                    <div class="text-sm space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between"><span>Ongkos Kirim</span><span id="shippingPrice">Rp0</span>
                        </div>
                        <div class="flex justify-between"><span>Diskon Belanja</span><span id="discountSubtotal">-Rp0</span>
                        </div>
                        <div class="flex justify-between"><span>Diskon Ongkir</span><span id="discountShipping">-Rp0</span>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 text-base sm:text-lg font-bold">
                        <span>Total</span>
                        <span id="totalPrice">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white shadow rounded-xl p-5 sm:p-6">
                    <h2 class="font-bold text-gray-800 text-lg sm:text-xl mb-3">Metode Pembayaran</h2>
                    <div class="space-y-2">
                        @foreach ($paymentMethods as $pm)
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="payment_method_id" value="{{ $pm->id }}"
                                    data-code="{{ $pm->code }}" data-name="{{ $pm->name }}"
                                    @if ($loop->first) checked @endif>
                                <span>{{ $pm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="paymentCodeBox" class="mt-4 hidden p-3 bg-gray-50 rounded-lg border">
                        <p class="text-sm text-gray-600">Kode pembayaran:</p>
                        <p id="paymentCodeText" class="font-semibold text-lg text-blue-700">-</p>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-3">
                    <button id="btnPayNow" type="button"
                        class="flex-1 bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                        Bayar Sekarang
                    </button>
                    <a href="{{ request('return_to', url()->previous()) }}"
                        class="flex-1 text-center bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">
                        ← Kembali
                    </a>
                    <button type="button" id="btnVoucher"
                        class="flex-1 bg-yellow-500 text-white py-3 rounded-xl font-semibold hover:bg-yellow-600 transition">
                        🎟 Voucher
                    </button>
                </div>
            </div>
        </div>
    </form>


    <div id="voucherModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-75 backdrop-blur-sm hidden">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all sm:my-8 sm:w-full">

            <div class="p-6 bg-blue-600 text-white flex items-center justify-between">
                <h3 class="text-2xl font-bold flex items-center">
                    Voucher Tersedia
                </h3>
                <button id="btnCloseVoucherHeader" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="voucherContent" class="p-6 overflow-y-auto max-h-96 space-y-4">
                <div id="voucherLoading" class="flex items-center justify-center p-8 text-gray-500">
                    <span class="animate-spin text-blue-500 mr-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 4a8 8 0 010 16" stroke-width="4" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span>Memuat voucher...</span>
                </div>

                <div id="voucherEmpty" class="hidden text-center p-8">
                    <img src="" alt="No vouchers available" class="mx-auto mb-4 opacity-75">
                    <h4 class="text-xl font-semibold text-gray-700">Tidak Ada Voucher Tersedia</h4>
                    <p class="text-gray-500">Tetap ikuti terus promo terbaru dari kami!</p>
                </div>

            </div>

            <div class="p-4 bg-gray-100 border-t flex justify-between">
                <button id="btnCloseVoucherFooter"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                    Tutup
                </button>
                <button id="btnApplyVoucher"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Gunakan Voucher
                </button>
            </div>

        </div>
    </div>


    <!-- Modal Info -->
    <div id="infoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div
            class="bg-white rounded-lg shadow-xl w-full max-w-sm sm:max-w-md md:max-w-lg p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
            <h3 id="infoModalTitle" class="text-lg font-bold mb-2">Info</h3>
            <p id="infoModalBody" class="text-gray-600 mb-4">Body</p>
            <div class="flex justify-end">
                <button onclick="closeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">OK</button>
            </div>
        </div>
    </div>

    <!-- Modal QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div
            class="bg-white rounded-xl shadow-xl w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold">Pembayaran QRIS</h3>
                <button id="btnCancelPay" class="text-red-600 hover:underline">Batal</button>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Kode Pesanan:</p>
                    <p id="modalKodePesanan" class="font-semibold">-</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Total yang harus dibayar:</p>
                    <p id="modalTotal" class="text-xl font-bold text-green-700">Rp0</p>
                </div>

                <div class="border rounded-lg p-3 text-center">
                    <img id="qrisImage" src="" alt="QRIS" class="mx-auto max-h-64 object-contain">
                    <p class="text-xs text-gray-500 mt-2">Scan QRIS ini menggunakan aplikasi e-wallet/banking Anda.</p>
                </div>

                <div class="mt-2">
                    <label class="block font-semibold mb-1">Upload bukti pembayaran (jpg/png)</label>
                    <input type="file" id="proofFile" accept="image/*" class="w-full border rounded p-2">
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                    <button id="btnSubmitProof"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg w-full sm:w-auto">Kirim Bukti</button>
                    <button id="btnCloseQris" class="px-4 py-2 bg-gray-200 rounded-lg w-full sm:w-auto">Tutup</button>
                </div>

                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mt-2">
                    Setelah mengirim bukti, status akan menjadi <b>menunggu konfirmasi</b>. Silakan cek Profil Anda.
                </p>
            </div>
        </div>
    </div>


    <!-- Modal Non-QRIS (Bank / E-Wallet) -->
    <div id="otherPaymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div
            class="bg-white rounded-xl shadow-xl w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold">Pembayaran Transfer / E-Wallet</h3>
                <button id="btnCancelOtherPay" class="text-red-600 hover:underline">Batal</button>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Kode Pesanan:</p>
                    <p id="otherModalKodePesanan" class="font-semibold">-</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Total yang harus dibayar:</p>
                    <p id="otherModalTotal" class="text-xl font-bold text-green-700">Rp0</p>
                </div>

                <div class="border rounded-lg p-3 text-center">
                    <p class="text-sm text-gray-600">Nomor Tujuan Pembayaran:</p>
                    <p id="paymentNumber" class="text-2xl font-bold text-blue-700 break-all">-</p>
                    <p id="paymentInstructions" class="text-xs text-gray-500 mt-1">Gunakan nomor ini untuk transfer.</p>
                </div>

                <div class="mt-2">
                    <label class="block font-semibold mb-1">Upload bukti pembayaran (jpg/png)</label>
                    <input type="file" id="otherProofFile" accept="image/*" class="w-full border rounded p-2">
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                    <button id="btnSubmitOtherProof"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg w-full sm:w-auto">
                        Kirim Bukti
                    </button>
                    <button id="btnCloseOther" class="px-4 py-2 bg-gray-200 rounded-lg w-full sm:w-auto">
                        Tutup
                    </button>
                </div>

                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mt-2">
                    Setelah mengirim bukti, status akan menjadi <b>menunggu konfirmasi</b>. Silakan cek Profil Anda.
                </p>
            </div>
        </div>
    </div>



    <script>
        function showModal(title, body) {
            document.getElementById('infoModalTitle').innerText = title;
            document.getElementById('infoModalBody').innerText = body;
            document.getElementById('infoModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // === wilayah (tetap sama seperti versi Anda) ===
            const baseWilayah = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            const provinsiEl = document.getElementById('provinsi');
            const kotaEl = document.getElementById('kota');
            const kecEl = document.getElementById('kecamatan');
            const kelEl = document.getElementById('kelurahan');

            async function fetchJson(url) {
                const res = await fetch(url);
                return res.json();
            }

            async function loadProvinces(selectedName = null) {
                provinsiEl.innerHTML = '<option value="">Pilih Provinsi</option>';
                try {
                    const list = await fetchJson(baseWilayah + '/provinces.json');
                    list.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.name;
                        opt.textContent = p.name;
                        opt.dataset.id = p.id;
                        if (selectedName && selectedName === p.name) opt.selected = true;
                        provinsiEl.appendChild(opt);
                    });
                    if (provinsiEl.value) await loadRegencies(provinsiEl.selectedOptions[0].dataset.id);
                } catch (e) {
                    provinsiEl.innerHTML = '<option value="">Gagal load provinsi</option>';
                }
            }
            async function loadRegencies(provId, selectedName = null) {
                kotaEl.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                kecEl.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelEl.innerHTML = '<option value="">Pilih Kelurahan</option>';
                try {
                    const list = await fetchJson(baseWilayah + '/regencies/' + provId + '.json');
                    list.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.name;
                        opt.textContent = k.name;
                        opt.dataset.id = k.id;
                        if (selectedName && selectedName === k.name) opt.selected = true;
                        kotaEl.appendChild(opt);
                    });
                    if (kotaEl.value) await loadDistricts(kotaEl.selectedOptions[0].dataset.id);
                } catch (e) {
                    kotaEl.innerHTML = '<option value="">Gagal load kota</option>';
                }
            }
            async function loadDistricts(kotaId, selectedName = null) {
                kecEl.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelEl.innerHTML = '<option value="">Pilih Kelurahan</option>';
                try {
                    const list = await fetchJson(baseWilayah + '/districts/' + kotaId + '.json');
                    list.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.name;
                        opt.textContent = d.name;
                        opt.dataset.id = d.id;
                        if (selectedName && selectedName === d.name) opt.selected = true;
                        kecEl.appendChild(opt);
                    });
                    if (kecEl.value) await loadVillages(kecEl.selectedOptions[0].dataset.id);
                } catch (e) {
                    kecEl.innerHTML = '<option value="">Gagal load kecamatan</option>';
                }
            }
            async function loadVillages(kecId, selectedName = null) {
                kelEl.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                try {
                    const list = await fetchJson(baseWilayah + '/villages/' + kecId + '.json');
                    list.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.name;
                        opt.textContent = v.name;
                        opt.dataset.id = v.id;
                        if (selectedName && selectedName === v.name) opt.selected = true;
                        kelEl.appendChild(opt);
                    });
                } catch (e) {
                    kelEl.innerHTML = '<option value="">Gagal load kelurahan</option>';
                }
            }
            // event wilayah
            provinsiEl.addEventListener('change', () => {
                const provId = provinsiEl.selectedOptions[0]?.dataset.id;
                if (provId) loadRegencies(provId);
                filterKurir();
            });
            kotaEl.addEventListener('change', () => {
                const kotaId = kotaEl.selectedOptions[0]?.dataset.id;
                if (kotaId) loadDistricts(kotaId);
                filterKurir();
            });
            kecEl.addEventListener('change', () => {
                const kecId = kecEl.selectedOptions[0]?.dataset.id;
                if (kecId) loadVillages(kecId);
            });

            loadProvinces();

            // existing address behavior (tetap seperti versi Anda) ...
            document.getElementById('existingAddress').addEventListener('change', async function() {
                const sel = this.selectedOptions[0];
                if (!this.value) {
                    document.getElementById('label').value = '';
                    document.getElementById('telepon').value = '';
                    document.getElementById('alamat_lengkap').value = '';
                    document.getElementById('user_address_id').value = '';
                    provinsiEl.innerHTML = '<option value="">Pilih Provinsi</option>';
                    kotaEl.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    kecEl.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    kelEl.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    await loadProvinces();
                    return;
                }
                document.getElementById('label').value = sel.dataset.label || '';
                document.getElementById('telepon').value = sel.dataset.telepon || '';
                document.getElementById('alamat_lengkap').value = sel.dataset.alamat || '';
                document.getElementById('user_address_id').value = this.value || '';

                const provinsi = sel.dataset.provinsi;
                const kota = sel.dataset.kota;
                const kecamatan = sel.dataset.kecamatan;
                const kelurahan = sel.dataset.kelurahan;

                if (provinsi) await loadProvinces(provinsi);
                if (kota) {
                    const provId = document.getElementById('provinsi').selectedOptions[0]?.dataset.id;
                    if (provId) await loadRegencies(provId, kota);
                }
                if (kecamatan) {
                    const kotaId = document.getElementById('kota').selectedOptions[0]?.dataset.id;
                    if (kotaId) await loadDistricts(kotaId, kecamatan);
                }
                if (kelurahan) {
                    const kecId = document.getElementById('kecamatan').selectedOptions[0]?.dataset.id;
                    if (kecId) await loadVillages(kecId, kelurahan);
                }

                filterKurir();
            });

            document.getElementById('saveAddress').addEventListener('click', async function() {
                const payload = {
                    label: document.getElementById('label').value,
                    telepon: document.getElementById('telepon').value,
                    alamat_lengkap: document.getElementById('alamat_lengkap').value,
                    provinsi: document.getElementById('provinsi').value,
                    kota: document.getElementById('kota').value,
                    kecamatan: document.getElementById('kecamatan').value,
                    kelurahan: document.getElementById('kelurahan').value
                };
                try {
                    const res = await fetch("{{ route('user.addresses.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success) {
                        // alert('Alamat tersimpan');
                        showModal('Alamat tersimpan', 'Alamat pengiriman berhasil disimpan.');
                        const opt = document.createElement('option');
                        opt.value = data.address.id;
                        opt.text = data.address.label + ' - ' + data.address.alamat_lengkap;
                        opt.dataset.label = data.address.label;
                        opt.dataset.telepon = data.address.telepon;
                        opt.dataset.alamat = data.address.alamat_lengkap;
                        opt.dataset.kecamatan = data.address.kecamatan;
                        opt.dataset.kota = data.address.kota;
                        opt.dataset.provinsi = data.address.provinsi;
                        opt.dataset.kelurahan = data.address.kelurahan;
                        document.getElementById('existingAddress').appendChild(opt);
                        document.getElementById('existingAddress').value = data.address.id;
                        document.getElementById('user_address_id').value = data.address.id;
                    } else {
                        // alert('Gagal menyimpan alamat');
                        showModal('Gagal', 'Gagal menyimpan alamat');
                    }
                } catch (err) {
                    // console.error(err);
                    // alert('Gagal menyimpan alamat (cek console)');
                    showModal('Gagal', 'Gagal menyimpan alamat');
                }
            });

            document.getElementById('useAddress').addEventListener('click', function() {
                const sel = document.getElementById('existingAddress').value;
                if (sel) {
                    document.getElementById('user_address_id').value = sel;
                    showModal('Alamat dipilih', 'Alamat pengiriman berhasil dipakai.');
                } else {
                    showModal('Alamat belum tersimpan', 'Silakan simpan alamat dulu.');
                }
            });

            // === kurir & total ===
            const kurirSelect = document.getElementById('kurir_id');
            const shippingPriceEl = document.getElementById('shippingPrice');
            const totalPriceEl = document.getElementById('totalPrice');

            function filterKurir() {
                const prov = provinsiEl.value?.toLowerCase();
                const kota = kotaEl.value?.toLowerCase();

                // Daftar provinsi yang ada di Pulau Jawa
                const pulauJawa = [
                    "banten",
                    "dki jakarta",
                    "jawa barat",
                    "jawa tengah",
                    "di yogyakarta",
                    "jawa timur"
                ];

                // Jika tidak ada provinsi dipilih
                if (!prov) {
                    // hapus opsi dummy luar jawa kalau ada
                    const dummy = kurirSelect.querySelector("option[value='luar_jawa_info']");
                    if (dummy) dummy.remove();

                    // tampilkan semua kurir normal
                    Array.from(kurirSelect.options).forEach(opt => {
                        opt.hidden = false;
                    });

                    // pilih opsi pertama yang normal biar gak kosong
                    const firstNormal = Array.from(kurirSelect.options).find(o => o.value && o.value !==
                        "luar_jawa_info");
                    if (firstNormal) {
                        kurirSelect.value = firstNormal.value;
                    } else {
                        kurirSelect.value = "";
                    }
                    return;
                }

                // Jika bukan provinsi di Pulau Jawa
                if (!pulauJawa.includes(prov)) {
                    // sembunyikan semua kurir
                    Array.from(kurirSelect.options).forEach(opt => {
                        opt.hidden = true;
                    });

                    // tambah opsi dummy info luar jawa
                    let dummy = kurirSelect.querySelector("option[value='luar_jawa_info']");
                    if (!dummy) {
                        dummy = document.createElement("option");
                        dummy.value = "luar_jawa_info";
                        dummy.textContent = "Luar Pulau Jawa belum tersedia / hubungi CS kami";
                        kurirSelect.appendChild(dummy);
                    }
                    kurirSelect.value = "luar_jawa_info";
                    return;
                }

                // --- kondisi normal dalam pulau jawa ---
                const dummy = kurirSelect.querySelector("option[value='luar_jawa_info']");
                if (dummy) dummy.remove();

                Array.from(kurirSelect.options).forEach(opt => {
                    const ket = opt.dataset.ket?.toLowerCase() || '';
                    opt.hidden = false; // reset dulu

                    if (kota && kota.includes("malang")) {
                        opt.hidden = !ket.includes("malang");
                    } else if (prov.includes("jawa timur")) {
                        opt.hidden = !ket.includes("luar kota");
                    } else {
                        opt.hidden = !ket.includes("luar jawa timur");
                    }
                });

                // pilih kurir pertama yg visible
                const firstVisible = Array.from(kurirSelect.options).find(o => !o.hidden);
                if (firstVisible) kurirSelect.value = firstVisible.value;

            }


            function parseDiscount(valueText) {
                if (!valueText) return 0;

                if (valueText.includes("Gratis Ongkir")) {
                    return "free_shipping"; // penanda khusus
                }

                let num = valueText.replace(/[^\d]/g, "");
                return parseInt(num) || 0;
            }

            const BASE_SUBTOTAL = {{ $subtotal }};
            const BASE_TOTAL_BERAT = {{ $totalBerat }};

            function updateTotals() {
                const subtotal = BASE_SUBTOTAL;
                const totalBerat = BASE_TOTAL_BERAT;
                const shippingBase = parseInt(kurirSelect.selectedOptions[0]?.dataset.price || 0);

                // hitung ongkir berdasarkan berat
                let beratKg = Math.ceil(totalBerat / 1000);
                let shipping = shippingBase * beratKg;

                // ====== ambil diskon dari voucher ======
                const subtotalDiscText = document.getElementById("discountSubtotal").innerText;
                const shippingDiscText = document.getElementById("discountShipping").innerText;
                let subtotalDiscount = parseDiscount(subtotalDiscText);
                let shippingDiscount = parseDiscount(shippingDiscText);

                // kalau free_shipping → ongkir 0
                if (shippingDiscount === "free_shipping") {
                    shipping = 0;
                } else {
                    shipping = Math.max(0, shipping - shippingDiscount);
                }

                let finalTotal = Math.max(0, (subtotal - subtotalDiscount) + shipping);

                // update tampilan
                shippingPriceEl.textContent = 'Rp' + shipping.toLocaleString('id-ID');
                totalPriceEl.textContent = 'Rp' + finalTotal.toLocaleString('id-ID');

                return {
                    finalTotal,
                    shipping,
                    subtotalDiscount,
                    shippingDiscount,
                    isFreeShipping: shippingDiscount === "free_shipping"
                };
            }


            kurirSelect.addEventListener('change', updateTotals);
            updateTotals();

            // ====== QRIS Modal Handlers ======
            const qrisModal = document.getElementById('qrisModal');
            const btnPayNow = document.getElementById('btnPayNow');
            const btnCancelPay = document.getElementById('btnCancelPay');
            const btnCloseQris = document.getElementById('btnCloseQris');
            const btnSubmitProof = document.getElementById('btnSubmitProof');
            const btnSubmitOtherProof = document.getElementById('btnSubmitOtherProof');
            const proofFile = document.getElementById('proofFile');

            let currentOrderId = null;

            function openQrisModal() {
                qrisModal.classList.remove('hidden');
            }

            function closeQrisModal(redirect = true) {
                qrisModal.classList.add('hidden');
                if (redirect) {
                    window.location.href = document.referrer || "{{ url('/') }}";
                }
            }

            btnCloseQris.addEventListener('click', () => closeQrisModal(true));

            btnPayNow.addEventListener('click', async () => {
                const user_address_id = document.getElementById('user_address_id').value;
                if (!user_address_id) {
                    showModal('Alamat belum dipilih', 'Silakan pilih atau simpan alamat dulu.');
                    return;
                }

                const checkoutSource = document.getElementById("checkoutSource")?.value || "cart";

                let finalTotal = 0,
                    shipping = 0,
                    subtotalDiscount = 0,
                    shippingDiscount = 0,
                    isFreeShipping = false;

                if (checkoutSource === "buy_now") {
                    // ✅ ambil langsung harga snapshot Buy Now
                    const price = parseFloat(document.getElementById("buyNowPrice")?.value || 0);
                    const qty = parseInt(document.getElementById("buyNowQty")?.value || 1);
                    const baseTotal = price * qty;

                    // ✅ ambil ongkir dari pilihan kurir (harus dihitung juga)
                    shipping = parseFloat(kurirSelect?.selectedOptions[0]?.dataset?.price || 0);

                    finalTotal = baseTotal + shipping;
                } else {
                    // ✅ default cart
                    ({
                        finalTotal,
                        shipping,
                        subtotalDiscount,
                        shippingDiscount,
                        isFreeShipping
                    } = updateTotals());
                }


                const form = document.getElementById('checkoutForm');
                const formData = new FormData(form);
                formData.append('shipping_cost', shipping);
                formData.append('total', finalTotal);
                formData.append('kurir_id', kurirSelect.value);

                // voucher (opsional)
                formData.append('voucher_subtotal_id', document.getElementById("voucherSubtotalId")
                    ?.value || "");
                formData.append('voucher_shipping_id', document.getElementById("voucherShippingId")
                    ?.value || "");
                formData.append('subtotal_discount', subtotalDiscount);
                formData.append('shipping_discount', isFreeShipping ? shipping : shippingDiscount);
                formData.append('is_free_shipping', isFreeShipping ? 1 : 0);

                try {
                    let res = await fetch("{{ route('checkout.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    let data = await res.json();
                    // console.log("Checkout.store response:", data);

                    if (data.errors) {
                        let messages = Object.values(data.errors).flat().join("\n");
                        showModal('Validasi gagal', messages);
                        return;
                    }
                    if (!data.success) {
                        showModal('Error', data.message || 'Gagal membuat order');
                        return;
                    }

                    currentOrderId = data.order_id;

                    // lanjut ke pembayaran
                    let payRes = await fetch("{{ route('checkout.pay') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: currentOrderId,
                            total: finalTotal
                        })
                    });

                    let payJson = await payRes.json();
                    // console.log("Checkout.pay response:", payJson);

                    if (!payJson.success) {
                        showModal('Error', payJson.message || 'Gagal menyiapkan pembayaran');
                        return;
                    }


                    document.getElementById('modalKodePesanan').textContent = payJson.kode_pesanan;
                    document.getElementById('modalTotal').textContent = 'Rp' + parseInt(finalTotal)
                        .toLocaleString('id-ID');

                    if (payJson.method === 'qris') {
                        document.getElementById('qrisImage').src = payJson.qris_image_url || '';
                        openQrisModal();
                    } else {
                        document.getElementById('otherModalKodePesanan').textContent = payJson
                            .kode_pesanan;
                        document.getElementById('otherModalTotal').textContent = 'Rp' + parseInt(
                            finalTotal).toLocaleString('id-ID');
                        document.getElementById('paymentNumber').textContent = payJson.method || '-';
                        document.getElementById('paymentInstructions').textContent = payJson
                            .instructions || 'Silakan lakukan pembayaran sesuai metode ini.';
                        openOtherPaymentModal();
                    }
                } catch (err) {
                    // console.error("Exception in checkout:", err);
                    showModal('Error', 'Terjadi kesalahan koneksi.');
                }
            });


            function openOtherPaymentModal() {
                document.getElementById('otherPaymentModal').classList.remove('hidden');
            }

            function closeOtherPaymentModal(redirect = true) {
                document.getElementById('otherPaymentModal').classList.add('hidden');
                if (redirect) {
                    window.location.href = document.referrer || "{{ url('/') }}";
                }
            }
            document.getElementById('btnCloseOther').addEventListener('click', () => closeOtherPaymentModal(true));
            document.getElementById('btnCancelOtherPay').addEventListener('click', () => closeOtherPaymentModal(
                true));

            // Cancel Other Payment → set cancelled + redirect ke halaman sebelumnya
            btnCancelOtherPay.addEventListener('click', async () => {
                if (!currentOrderId) {
                    closeOtherPaymentModal(true);
                    window.location.href = document.referrer || "{{ url('/') }}";
                    return;
                }
                try {
                    let res = await fetch("{{ route('checkout.cancel') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: currentOrderId
                        })
                    });
                    let json = await res.json();

                    if (!json.success) {
                        showModal('Gagal batal', json.message || 'Tidak bisa membatalkan order.');
                        return;
                    }

                    // ✅ redirect ke halaman sebelumnya
                    window.location.href = document.referrer || "{{ url('/') }}";

                } catch (e) {
                    showModal('Error', 'Gagal membatalkan order.');
                }
            });

            // Cancel QRIS → set cancelled + redirect ke halaman sebelumnya
            btnCancelPay.addEventListener('click', async () => {
                if (!currentOrderId) {
                    closeQrisModal(true);
                    window.location.href = document.referrer || "{{ url('/') }}";
                    return;
                }
                try {
                    let res = await fetch("{{ route('checkout.cancel') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: currentOrderId
                        })
                    });
                    let json = await res.json();

                    if (!json.success) {
                        showModal('Gagal batal', json.message || 'Tidak bisa membatalkan order.');
                        return;
                    }

                    // ✅ redirect ke halaman sebelumnya
                    window.location.href = document.referrer || "{{ url('/') }}";

                } catch (e) {
                    showModal('Error', 'Gagal membatalkan order.');
                }
            });



            // === Submit bukti QRIS ===
            btnSubmitProof.addEventListener('click', async () => {
                if (!currentOrderId) {
                    showModal('Error', 'Order tidak ditemukan.');
                    return;
                }

                const file = proofFile.files[0];
                if (!file) {
                    showModal('Error', 'Silakan pilih file bukti pembayaran dulu.');
                    return;
                }

                const formData = new FormData();
                formData.append('order_id', currentOrderId);
                formData.append('proof', file);

                try {
                    let res = await fetch("{{ route('checkout.upload_proof') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    let json = await res.json();

                    if (!json.success) {
                        showModal('Error', json.message || 'Gagal upload bukti.');
                        return;
                    }

                    closeQrisModal(false);
                    window.location.href = "{{ url('/profil') }}"; // ✅ redirect ke profil

                } catch (err) {
                    showModal('Error', 'Terjadi kesalahan koneksi.');
                }
            });

            // === Submit bukti Non-QRIS ===
            btnSubmitOtherProof.addEventListener('click', async () => {
                if (!currentOrderId) {
                    showModal('Error', 'Order tidak ditemukan.');
                    return;
                }

                const file = document.getElementById('otherProofFile').files[0];
                if (!file) {
                    showModal('Error', 'Silakan pilih file bukti pembayaran dulu.');
                    return;
                }

                const formData = new FormData();
                formData.append('order_id', currentOrderId);
                formData.append('proof', file);

                try {
                    let res = await fetch("{{ route('checkout.upload_proof') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    let json = await res.json();

                    if (!json.success) {
                        showModal('Error', json.message || 'Gagal upload bukti.');
                        return;
                    }

                    closeOtherPaymentModal(false);
                    window.location.href = "{{ url('/profil') }}"; // ✅ redirect ke profil

                } catch (err) {
                    showModal('Error', 'Terjadi kesalahan koneksi.');
                }
            });
            // === Voucher ===
            // Pastikan semua elemen HTML sudah teridentifikasi dengan benar
            const voucherBtn = document.getElementById('btnVoucher');
            const voucherModal = document.getElementById('voucherModal');
            const voucherContent = document.getElementById('voucherContent');
            const voucherLoading = document.getElementById('voucherLoading');
            const voucherEmpty = document.getElementById('voucherEmpty');
            const btnCloseVoucherHeader = document.getElementById("btnCloseVoucherHeader");
            const btnCloseVoucherFooter = document.getElementById("btnCloseVoucherFooter");
            // simpan daftar voucher saat load
            window.voucherData = [];

            // Fungsi untuk menutup modal
            function closeVoucherModal() {
                if (voucherModal) {
                    voucherModal.classList.add("hidden");
                }
            }

            // Fungsi untuk membuka modal dan memuat data
            function openVoucherModal() {
                if (voucherModal) {
                    // console.log("🔔 Membuka modal voucher");
                    voucherModal.classList.remove('hidden');
                    loadVoucher();
                }
            }

            // Event listeners
            if (voucherBtn) {
                voucherBtn.addEventListener('click', openVoucherModal);
            }
            if (btnCloseVoucherHeader) {
                btnCloseVoucherHeader.addEventListener("click", closeVoucherModal);
            }
            if (btnCloseVoucherFooter) {
                btnCloseVoucherFooter.addEventListener("click", closeVoucherModal);
            }



            // 🔎 Load voucher
            async function loadVoucher() {
                voucherContent.classList.add("hidden");
                voucherLoading.classList.remove("hidden");
                voucherEmpty.classList.add("hidden");


                try {
                    let res = await fetch("{{ route('voucher.check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    });
                    let data = await res.json();

                    // simpan semua data voucher
                    window.voucherData = data.vouchers;
                    voucherLoading.classList.add("hidden");

                    if (!data.success || data.vouchers.length === 0) {
                        voucherContent.classList.add("hidden");
                        voucherEmpty.classList.remove("hidden");
                        return;
                    }

                    // Reset isi utama
                    voucherContent.classList.remove("hidden");
                    voucherContent.innerHTML = `
            <div id="voucher-subtotal">
                <h3 class="font-bold text-lg mb-2">Voucher Belanja</h3>
                <div class="space-y-3" id="voucher-subtotal-list"></div>
            </div>
            <div id="voucher-shipping" class="mt-4">
                <h3 class="font-bold text-lg mb-2">Voucher Ongkir</h3>
                <div class="space-y-3" id="voucher-shipping-list"></div>
            </div>
            <div id="voucher-claim" class="mt-6">
                <h3 class="font-bold text-lg mb-2 text-blue-600">Voucher Belum Diklaim</h3>
                <div class="space-y-3" id="voucher-claim-list"></div>
            </div>
            <div id="voucher-used" class="mt-6">
                <h3 class="font-bold text-lg mb-2 text-gray-500">Voucher Sudah Digunakan</h3>
                <div class="space-y-3" id="voucher-used-list"></div>
            </div>
        `;

                    let subtotalList = document.getElementById("voucher-subtotal-list");
                    let shippingList = document.getElementById("voucher-shipping-list");
                    let claimList = document.getElementById("voucher-claim-list");
                    let usedList = document.getElementById("voucher-used-list");

                    // Flag cek isi list
                    let hasSubtotal = false;
                    let hasShipping = false;
                    let hasClaim = false;
                    let hasUsed = false;

                    data.vouchers.forEach(v => {
                        let val = Number(v.value) || 0;

                        // Format tampilan nilai
                        let valueDisplay = "";
                        switch (v.discount_type) {
                            case "percent":
                                valueDisplay = `${val}%`;
                                break;
                            case "nominal":
                                valueDisplay = `Rp ${val.toLocaleString("id-ID")}`;
                                break;
                            case "free_shipping":
                                valueDisplay = "Gratis Ongkir";
                                break;
                            case "shipping_discount":
                                valueDisplay = `Diskon Ongkir Rp ${val.toLocaleString("id-ID")}`;
                                break;
                            default:
                                valueDisplay = "-";
                        }

                        let voucherHtml = "";

                        if (v.used) {
                            hasUsed = true;
                            voucherHtml = `
                    <div class="voucher-card p-4 border-2 border-dashed rounded-lg bg-gray-100 text-gray-400">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-lg">${v.name}</h4>
                                <p class="text-sm">Sudah digunakan</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-300">Digunakan</span>
                        </div>
                    </div>
                `;
                            usedList.innerHTML += voucherHtml;
                            return;
                        }

                        if (!v.claimed) {
                            hasClaim = true;
                            voucherHtml = `
                    <div class="voucher-card p-4 border-2 border-blue-500 border-dashed rounded-lg bg-blue-50 text-blue-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-lg">${v.name}</h4>
                                <p class="text-sm">${valueDisplay}</p>
                            </div>
                            <button onclick="claimVoucher(${v.id})"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Klaim
                            </button>
                        </div>
                    </div>
                `;
                            claimList.innerHTML += voucherHtml;
                            return;
                        }

                        // Sudah klaim → voucher aktif
                        let radioName = ["percent", "nominal"].includes(v.discount_type) ?
                            "voucher_subtotal_selected" :
                            "voucher_shipping_selected";

                        voucherHtml = `
                <div class="voucher-card p-4 border-2 border-green-500 border-dashed rounded-lg bg-green-50 text-green-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg">${v.name}</h4>
                            <p class="text-sm">${valueDisplay}</p>
                        </div>
                        <label class="flex items-center gap-2">
                            <input type="radio"
                                   name="${radioName}"
                                   value="${v.id}"
                                   class="voucher-radio">
                            <span>Pilih Voucher</span>
                        </label>
                    </div>
                </div>
            `;

                        if (["percent", "nominal"].includes(v.discount_type)) {
                            hasSubtotal = true;
                            subtotalList.innerHTML += voucherHtml;
                        } else if (["free_shipping", "shipping_discount"].includes(v.discount_type)) {
                            hasShipping = true;
                            shippingList.innerHTML += voucherHtml;
                        }
                    });

                    // Fallback teks kalau list kosong
                    if (!hasSubtotal) subtotalList.innerHTML =
                        `<p class="text-gray-500 italic">Tidak ada voucher belanja</p>`;
                    if (!hasShipping) shippingList.innerHTML =
                        `<p class="text-gray-500 italic">Tidak ada voucher ongkir</p>`;
                    if (!hasClaim) claimList.innerHTML =
                        `<p class="text-gray-500 italic">Tidak ada voucher untuk diklaim</p>`;
                    if (!hasUsed) usedList.innerHTML =
                        `<p class="text-gray-500 italic">Belum ada voucher digunakan</p>`;

                } catch (err) {
                    // console.error("⚠️ Error loadVoucher:", err);
                    voucherLoading.classList.add("hidden");
                    voucherContent.innerHTML =
                        `<p class="text-red-600 text-center">Gagal memuat voucher. Silakan coba lagi.</p>`;
                }
            }

            // 📌 Pastikan fungsi global
            window.claimVoucher = async function(id) {
                // console.log("📝 Mengklaim voucher id:", id);

                try {
                    let res = await fetch("{{ route('voucher.claim') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voucher_id: id
                        })
                    });

                    let data = await res.json();
                    // console.log("📩 Respon klaim:", data);

                    if (data.success) {
                        showToast("✅ " + data.message, "success");
                        loadVoucher(); // refresh daftar voucher
                    } else {
                        showToast("❌ Gagal klaim: " + data.message, "error");
                    }
                } catch (err) {
                    // console.error("⚠️ Error klaim:", err);
                    showToast("Terjadi kesalahan saat klaim voucher.", "error");
                }
            }

            const btnApplyVoucher = document.getElementById("btnApplyVoucher");


            btnApplyVoucher.addEventListener("click", () => {
                const selectedSubtotal = document.querySelector(
                    "input[name='voucher_subtotal_selected']:checked");
                const selectedShipping = document.querySelector(
                    "input[name='voucher_shipping_selected']:checked");

                let selectedVoucherSubtotal = selectedSubtotal ? selectedSubtotal.value : null;
                let selectedVoucherShipping = selectedShipping ? selectedShipping.value : null;

                // isi hidden input supaya btnPayNow bisa ambil
                document.getElementById("voucherSubtotalId").value = selectedVoucherSubtotal || "";
                document.getElementById("voucherShippingId").value = selectedVoucherShipping || "";

                // cari data voucher sesuai pilihan
                let voucherSubtotalObj = window.voucherData.find(v => v.id == selectedVoucherSubtotal);
                let voucherShippingObj = window.voucherData.find(v => v.id == selectedVoucherShipping);

                // format tampilan diskon subtotal
                if (voucherSubtotalObj) {
                    if (["percent", "nominal"].includes(voucherSubtotalObj.discount_type)) {
                        document.getElementById("discountSubtotal").innerText =
                            `-Rp${Number(voucherSubtotalObj.value).toLocaleString("id-ID")}`;
                    } else {
                        document.getElementById("discountSubtotal").innerText = "-Rp0";
                    }
                } else {
                    document.getElementById("discountSubtotal").innerText = "-Rp0";
                }

                // format tampilan diskon shipping
                if (voucherShippingObj) {
                    if (voucherShippingObj.discount_type === "free_shipping") {
                        document.getElementById("discountShipping").innerText = "Gratis Ongkir";
                    } else if (voucherShippingObj.discount_type === "shipping_discount") {
                        document.getElementById("discountShipping").innerText =
                            `-Rp${Number(voucherShippingObj.value).toLocaleString("id-ID")}`;
                    } else {
                        document.getElementById("discountShipping").innerText = "-Rp0";
                    }
                } else {
                    document.getElementById("discountShipping").innerText = "-Rp0";
                }

                // Tutup modal
                voucherModal.classList.add("hidden");

                // setelah update total
                let totals = updateTotals();

                fetch("/voucher/apply", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            vouchers: [selectedVoucherSubtotal, selectedVoucherShipping].filter(
                                Boolean),
                            subtotal: totals
                                .finalTotal // atau subtotal asli kalau mau sebelum diskon
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // console.log("✅ Voucher langsung digunakan:", data);
                        } else {
                            alert(data.message || "Gagal gunakan voucher");
                        }
                    })
                    .catch(
                        err =>
                        console.error("❌ Error apply voucher:", err)
                    );

            });


            // load voucher saat halaman siap
            loadVoucher();

        });
    </script>

    <!-- 📌 Tambahkan di layout utama (misalnya admin.main) -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3"></div>

    <script>
        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");

            // Warna sesuai tipe
            let bg = "bg-green-600";
            if (type === "error") bg = "bg-red-600";
            if (type === "info") bg = "bg-blue-600";

            // Elemen toast
            let toast = document.createElement("div");
            toast.className = `${bg} text-white px-4 py-2 rounded-lg shadow-md animate-fade-in`;
            toast.innerText = message;

            container.appendChild(toast);

            // Auto hilang setelah 3 detik
            setTimeout(() => {
                toast.classList.add("opacity-0", "transition-opacity");
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

@endsection
