@extends('admin.main')

@section('judul', 'Manajemen Diskon & Voucher | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Diskon & Voucher</h1>
            <button onclick="openModal('createModal')"
                class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 ease-in-out">
                Tambah Diskon/Voucher
            </button>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                                Diskon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Expired</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($discounts as $discount)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $discount->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($discount->type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $discount->discount_type ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($discount->discount_type === 'percent')
                                        {{ $discount->value }} %
                                    @elseif($discount->discount_type === 'nominal')
                                        Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @elseif($discount->discount_type === 'shipping_discount')
                                        Potongan Ongkir Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @else
                                        {{ $discount->value ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $discount->product?->nama_produk ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $discount->user?->name ?? 'Semua' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $discount->expired_at ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $discount->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $discount->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal('editModal-{{ $discount->id }}')"
                                            class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 ease-in-out">
                                            Edit
                                        </button>
                                        <button onclick="openModal('deleteModal-{{ $discount->id }}')"
                                            class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 ease-in-out">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div id="editModal-{{ $discount->id }}"
                                class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-600 bg-opacity-75 transition-opacity duration-300 ease-in-out">
                                <div class="flex items-center justify-center min-h-screen p-4">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
                                        <div class="flex justify-between items-center pb-4 border-b">
                                            <h2 class="text-2xl font-semibold text-gray-800">Edit Diskon/Voucher</h2>
                                            <button onclick="closeModal('editModal-{{ $discount->id }}')"
                                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('discounts.update', $discount->id) }}" method="POST"
                                            class="mt-4">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 font-medium mb-1">Nama</label>
                                                    <input type="text" name="name" value="{{ $discount->name }}"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 font-medium mb-1">Tipe</label>
                                                    <select name="type" id="typeEdit{{ $discount->id }}"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        onchange="toggleFields('edit', {{ $discount->id }})">
                                                        <option value="product"
                                                            @if ($discount->type == 'product') selected @endif>Diskon Produk
                                                        </option>
                                                        <option value="voucher"
                                                            @if ($discount->type == 'voucher') selected @endif>Voucher
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 font-medium mb-1">Jenis Diskon</label>
                                                    <select name="discount_type" id="discountTypeEdit{{ $discount->id }}"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        onchange="toggleValueField('edit', {{ $discount->id }})">
                                                        <option value="percent"
                                                            @if ($discount->discount_type == 'percent') selected @endif>Persen
                                                        </option>
                                                        <option value="nominal"
                                                            @if ($discount->discount_type == 'nominal') selected @endif>Nominal
                                                        </option>
                                                        <option value="free_shipping"
                                                            @if ($discount->discount_type == 'free_shipping') selected @endif>Gratis Ongkir
                                                        </option>
                                                        <option value="shipping_discount"
                                                            @if ($discount->discount_type == 'shipping_discount') selected @endif>Potongan
                                                            Ongkir</option>
                                                    </select>
                                                    <small class="mt-2 block text-xs text-gray-500">percent hanya untuk tipe
                                                        discount produk selain itu hanya pakai nominal dll.</small>
                                                </div>
                                                <div class="mb-2" id="valueField-edit-{{ $discount->id }}">
                                                    <label class="block text-gray-700 font-medium mb-1">Value</label>
                                                    <input type="number" name="value" value="{{ $discount->value }}"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        oninput="updatePricePreviewEdit({{ $discount->id }})">
                                                </div>
                                                <div class="mb-2" id="productSelectEdit{{ $discount->id }}">
                                                    <label class="block text-gray-700 font-medium mb-1">Produk</label>
                                                    <div class="relative">
                                                        <input type="text" id="productSearchEdit{{ $discount->id }}"
                                                            class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                            placeholder="Cari produk...">
                                                        <select name="product_id"
                                                            id="productSelectActualEdit-{{ $discount->id }}"
                                                            class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors mt-2"
                                                            onchange="updatePricePreviewEdit({{ $discount->id }})"
                                                            size="10">
                                                            <option value="">-- Pilih Produk --</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    data-harga="{{ $product->harga }}"
                                                                    @if ($discount->product_id == $product->id) selected @endif>
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-600"
                                                        id="pricePreviewEdit-{{ $discount->id }}"></div>
                                                </div>
                                                <div class="mb-2" id="userSelectEdit{{ $discount->id }}">
                                                    <label class="block text-gray-700 font-medium mb-1">User
                                                        (Opsional)</label>
                                                    <select name="user_id"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                        <option value="">Semua</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                @if ($discount->user_id == $user->id) selected @endif>
                                                                {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2" id="expiredEdit{{ $discount->id }}">
                                                    <label class="block text-gray-700 font-medium mb-1">Expired
                                                        (Voucher)</label>
                                                    <input type="date" name="expired_at"
                                                        value="{{ $discount->expired_at }}"
                                                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                </div>
                                                <div class="mb-4 col-span-1 md:col-span-2">
                                                    <label class="inline-flex items-center text-gray-700">
                                                        <input type="checkbox" name="status" value="1"
                                                            @if ($discount->status) checked @endif
                                                            class="rounded text-green-600 focus:ring-green-500">
                                                        <span class="ml-2">Aktif</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button"
                                                    onclick="closeModal('editModal-{{ $discount->id }}')"
                                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors duration-200">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="deleteModal-{{ $discount->id }}"
                                class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-600 bg-opacity-75 transition-opacity duration-300 ease-in-out">
                                <div class="flex items-center justify-center min-h-screen p-4">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md text-center transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
                                        <div class="flex justify-between items-center pb-4 border-b">
                                            <h2 class="text-2xl font-semibold text-gray-800">Hapus Diskon/Voucher</h2>
                                            <button onclick="closeModal('deleteModal-{{ $discount->id }}')"
                                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-4 mb-6 text-lg text-gray-600">Apakah Anda yakin ingin menghapus <b
                                                class="font-semibold text-gray-900">{{ $discount->name }}</b>?</p>
                                        <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-center gap-3">
                                                <button type="button"
                                                    onclick="closeModal('deleteModal-{{ $discount->id }}')"
                                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors duration-200">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="createModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-600 bg-opacity-75 transition-opacity duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h2 class="text-2xl font-semibold text-gray-800">Tambah Diskon/Voucher</h2>
                    <button onclick="closeModal('createModal')"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('discounts.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2">
                            <label class="block text-gray-700 font-medium mb-1">Nama</label>
                            <input type="text" name="name"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 font-medium mb-1">Tipe</label>
                            <select name="type" id="typeCreate"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                onchange="toggleFields('create')">
                                <option value="product">Diskon Produk</option>
                                <option value="voucher">Voucher</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 font-medium mb-1">Jenis Diskon</label>
                            <select name="discount_type" id="discountTypeCreate"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                onchange="toggleValueField('create')">
                                <option value="percent">Persen</option>
                                <option value="nominal">Nominal</option>
                                <option value="free_shipping">Gratis Ongkir</option>
                                <option value="shipping_discount">Potongan Ongkir</option>
                            </select>
                            <small class="mt-2 block text-xs text-gray-500">percent hanya untuk tipe discount produk selain
                                itu hanya pakai nominal dll.</small>

                        </div>
                        <div class="mb-2" id="valueField-create">
                            <label class="block text-gray-700 font-medium mb-1">Value</label>
                            <input type="number" name="value"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                oninput="updatePricePreview()">
                        </div>
                        <div class="mb-2" id="productSelectCreate">
                            <label class="block text-gray-700 font-medium mb-1">Produk</label>
                            <div class="relative">
                                <input type="text" id="productSearchCreate"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Cari produk...">
                                <select name="product_id" id="productSelectActualCreate"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors mt-2"
                                    onchange="updatePricePreview()" size="10">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-harga="{{ $product->harga }}">
                                            {{ $product->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2 text-sm text-gray-600" id="pricePreview"></div>
                        </div>
                        <div class="mb-2" id="userSelectCreate">
                            <label class="block text-gray-700 font-medium mb-1">User (Opsional)</label>
                            <select name="user_id"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Semua</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2" id="expiredCreate">
                            <label class="block text-gray-700 font-medium mb-1">Expired (Voucher)</label>
                            <input type="date" name="expired_at"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div class="mb-4 col-span-1 md:col-span-2">
                            <label class="inline-flex items-center text-gray-700">
                                <input type="checkbox" name="status" value="1" checked
                                    class="rounded text-green-600 focus:ring-green-500">
                                <span class="ml-2">Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" onclick="closeModal('createModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors duration-200">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div.transform').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('div.transform').classList.add('scale-100', 'opacity-100');
            }, 10);

            // Re-initialize fields for edit modal
            if (id.startsWith('editModal')) {
                const discountId = id.split('-')[1];
                toggleFields('edit', discountId);
                updatePricePreviewEdit(discountId);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.querySelector('div.transform').classList.remove('scale-100', 'opacity-100');
            modal.querySelector('div.transform').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        function toggleFields(mode, id = '') {
            const suffix = id ? capitalize(mode) + id : capitalize(mode);
            const type = document.getElementById('type' + suffix).value;
            const discountType = document.getElementById('discountType' + suffix);
            const productSelect = document.getElementById('productSelect' + suffix);
            const userSelect = document.getElementById('userSelect' + suffix);
            const expired = document.getElementById('expired' + suffix);

            if (type === 'product') {
                [...discountType.options].forEach(opt => {
                    if (opt.value === 'percent' || opt.value === 'nominal') opt.style.display = 'block';
                    else opt.style.display = 'none';
                });
                productSelect.style.display = 'block';
                userSelect.style.display = 'none';
                expired.style.display = 'none';
                discountType.value = discountType.querySelector('[value="percent"]').value;
            } else {
                [...discountType.options].forEach(opt => opt.style.display = 'block');
                productSelect.style.display = 'none';
                userSelect.style.display = 'block';
                expired.style.display = 'block';
            }
            toggleValueField(mode, id);
        }

        function toggleValueField(mode, id = '') {
            const suffix = id ? capitalize(mode) + id : capitalize(mode);
            const type = document.getElementById('discountType' + suffix).value;
            const valueField = document.getElementById('valueField-' + mode + (id ? `-${id}` : ''));
            if (type === 'free_shipping') {
                valueField.style.display = 'none';
            } else {
                valueField.style.display = 'block';
            }
        }

        function formatRupiah(angka) {
            return 'Rp ' + parseFloat(angka).toLocaleString('id-ID');
        }

        function updatePricePreview() {
            const select = document.getElementById('productSelectActualCreate');
            const preview = document.getElementById('pricePreview');
            const discountType = document.getElementById('discountTypeCreate').value;
            const value = parseFloat(document.querySelector('#valueField-create input[name="value"]').value) || 0;
            const harga = parseFloat(select.selectedOptions[0]?.dataset.harga || 0);

            if (!harga || discountType === 'free_shipping') {
                preview.innerHTML = '';
                return;
            }
            let finalPrice = harga;
            if (discountType === 'percent') {
                finalPrice = harga - (harga * value / 100);
            } else if (discountType === 'nominal') {
                finalPrice = harga - value;
            }
            if (finalPrice < 0) finalPrice = 0;

            preview.innerHTML =
                `Harga Asli: <b>${formatRupiah(harga)}</b><br>Setelah Diskon: <b>${formatRupiah(finalPrice)}</b>`;
        }

        function updatePricePreviewEdit(id) {
            const select = document.getElementById(`productSelectActualEdit-${id}`);
            const preview = document.getElementById(`pricePreviewEdit-${id}`);
            const discountType = document.getElementById(`discountTypeEdit${id}`).value;
            const value = parseFloat(document.querySelector(`#valueField-edit-${id} input[name="value"]`).value) || 0;
            const harga = parseFloat(select.selectedOptions[0]?.dataset.harga || 0);

            if (!harga || discountType === 'free_shipping') {
                preview.innerHTML = '';
                return;
            }
            let finalPrice = harga;
            if (discountType === 'percent') {
                finalPrice = harga - (harga * value / 100);
            } else if (discountType === 'nominal') {
                finalPrice = harga - value;
            }
            if (finalPrice < 0) finalPrice = 0;

            preview.innerHTML =
                `Harga Asli: <b>${formatRupiah(harga)}</b><br>Setelah Diskon: <b>${formatRupiah(finalPrice)}</b>`;
        }

        // Product Search Functionality
        function setupProductSearch(searchId, selectId) {
            const searchInput = document.getElementById(searchId);
            const selectElement = document.getElementById(selectId);
            const options = Array.from(selectElement.options);

            searchInput.addEventListener('input', () => {
                const filter = searchInput.value.toLowerCase();
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });
        }

        // Initial setup
        document.addEventListener('DOMContentLoaded', () => {
            toggleFields('create');
            setupProductSearch('productSearchCreate', 'productSelectActualCreate');

            document.querySelectorAll('tr').forEach(row => {
                const editButton = row.querySelector('button[onclick^="openModal(\'editModal"]');
                if (editButton) {
                    const id = editButton.getAttribute('onclick').match(/\d+/)[0];
                    const discountType = document.getElementById(`discountTypeEdit${id}`).value;
                    const type = document.getElementById(`typeEdit${id}`).value;
                    toggleFields('edit', id);
                    if (type === 'product' && (discountType === 'percent' || discountType === 'nominal')) {
                        updatePricePreviewEdit(id);
                    }
                    setupProductSearch(`productSearchEdit${id}`, `productSelectActualEdit-${id}`);
                }
            });
        });
    </script>
@endsection
