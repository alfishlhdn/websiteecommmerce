@extends('admin.main')

@section('judul', 'Kelola Pesanan | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">📦 Daftar Pesanan</h2>

        <!-- 🔹 Filter & Search -->
        <form method="GET" action="{{ route('admin.pesanan.index') }}" class="mb-6 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..."
                class="border p-2 rounded w-64">

            <select name="payment_status" class="border p-2 rounded">
                <option value="">-- Filter Pembayaran --</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="waiting_confirmation"
                    {{ request('payment_status') == 'waiting_confirmation' ? 'selected' : '' }}>
                    Waiting Confirmation</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                </option>
            </select>

            <select name="shipping_status" class="border p-2 rounded">
                <option value="">-- Filter Pengiriman --</option>
                <option value="pending" {{ request('shipping_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('shipping_status') == 'processing' ? 'selected' : '' }}>Processing
                </option>
                <option value="shipped" {{ request('shipping_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('shipping_status') == 'delivered' ? 'selected' : '' }}>Delivered
                </option>
                <option value="cancelled" {{ request('shipping_status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                </option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">🔍 Filter</button>
            <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2 bg-gray-300 rounded">Reset</a>
        </form>

        <div class="overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 text-sm uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Kode Pesanan</th>
                        <th class="px-4 py-3">Nama Pelanggan</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Pembayaran</th>
                        <th class="px-4 py-3">Pengiriman</th>
                        <th class="px-4 py-3">Resi</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $order->kode_pesanan }}</td>
                            <td class="px-4 py-3">{{ $order->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 space-y-1">
                                <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 block w-fit">
                                    Metode: {{ $order->paymentMethod->name ?? '-' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 block w-fit">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 space-y-1">
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700 block w-fit">
                                    Kurir: {{ $order->kurir->name ?? '-' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 block w-fit">
                                    {{ ucfirst($order->shipping_status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $order->nomor_resi ?? '-' }}</td>
                            <td class="px-4 py-3 flex gap-2 justify-center">
                                <button onclick="openDetailModal('{{ $order->kode_pesanan }}')"
                                    class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-xs shadow">
                                    📑 Detail
                                </button>

                                <button
                                    onclick="openUpdateModal({{ $order->id }}, '{{ $order->shipping_status }}', '{{ $order->payment_status }}', '{{ $order->nomor_resi }}')"
                                    class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-xs shadow">
                                    ✏️ Update
                                </button>


                                @if ($order->shipping_status !== 'delivered')
                                    <button onclick="openInvoice({{ $order->id }})"
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-xs shadow">
                                        🧾 Invoice
                                    </button>
                                @endif

                                <button onclick="confirmDelete({{ $order->id }})"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs shadow">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- 🔹 Modal Detail -->
    <div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-[500px] max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-bold mb-4">Detail Bukti Pembayaran</h3>
            <div id="detailContent" class="text-center">Loading...</div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal('detailModal')" class="px-3 py-1 bg-gray-300 rounded">Tutup</button>
            </div>
        </div>
    </div>
    <script>
        function openDetailModal(kode_pesanan) {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailContent').innerHTML = `<p class="text-gray-500">Loading...</p>`;

            fetch(`/pesanan/detail/${kode_pesanan}`)
                .then(res => res.json())
                .then(order => {
                    console.log(order); // 🔍 cek di console browser biar tahu struktur datanya

                    let content = `
        <p class="mb-3 font-semibold">Kode Pesanan: ${order.kode_pesanan}</p>
        <p class="mb-3 font-semibold">Catatan: ${order.catatan}</p>
        <p class="mb-3">${order.user.name} - ${order.user.phone}</p>
        <p class="mb-3">Total: Rp ${Number(order.total).toLocaleString('id-ID')}</p>
    `;

                    if (order.proof_path) {
                        content += `
            Bukti Pembayaran :
            <img src="/storage/${order.proof_path}" alt="Bukti Pembayaran" class="rounded border max-h-96 mx-auto mb-3">
        `;
                    } else {
                        content += `<p class="text-gray-500 mb-3">Tidak ada bukti pembayaran</p>`;
                    }

                    // Produk yang dipesan
                    content += `<h4 class="font-semibold mb-2">🛒 Produk Dipesan:</h4>`;
                    content += `<ul class="list-disc pl-5 text-sm text-gray-700">`;
                    order.items.forEach(item => {
                        content +=
                            `<li>${item.product.nama_produk} (x${item.jumlah}) - Rp ${Number(item.harga).toLocaleString('id-ID')}</li>`;
                    });
                    content += `</ul>`;

                    // Alamat Pembeli
                    if (order.address) {
                        content += `<h4 class="font-semibold mt-4 mb-2">📍 Alamat Pembeli:</h4>`;
                        content += `
            <div class="text-sm text-gray-700 space-y-1">
                <p>${order.address.alamat_lengkap ?? '-'}</p>
                <p>${order.address.kelurahan}, ${order.address.kecamatan}, ${order.address.kota ?? ''}, ${order.address.provinsi ?? ''}</p>
            </div>
        `;
                    } else {
                        content += `<p class="text-red-500">Alamat belum tersedia</p>`;
                    }

                    document.getElementById('detailContent').innerHTML = content;
                })

                .catch(err => {
                    document.getElementById('detailContent').innerHTML =
                        `<p class="text-red-500">Gagal memuat detail</p>`;
                });
        }
    </script>



    <!-- 🔹 Modal Update -->
    <div id="updateModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-bold mb-4">Update Order</h3>
            <form id="updateForm">
                @csrf
                <input type="hidden" id="order_id">

                <label class="block text-sm font-medium">Status Pembayaran</label>
                <select id="payment_status" class="w-full border p-2 rounded mb-3">
                    <option value="pending">Pending</option>
                    <option value="waiting_confirmation">Waiting Confirmation</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <label class="block text-sm font-medium">Status Pengiriman</label>
                <select id="shipping_status" class="w-full border p-2 rounded mb-3">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <div id="resiField" class="hidden">
                    <label class="block text-sm font-medium">Nomor Resi</label>
                    <input type="text" id="nomor_resi" class="w-full border p-2 rounded mb-3"
                        placeholder="Isi nomor resi">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('updateModal')"
                        class="px-3 py-1 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔹 Modal Invoice -->
    <div id="invoiceModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto relative z-50">

            <button onclick="closeModal('invoiceModal')"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 no-print">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div id="invoiceContent" class="print-area">
                <p class="text-center text-gray-500">Memuat data...</p>
            </div>

            <div class="mt-4 flex justify-end gap-2 no-print">
                <button onclick="closeModal('invoiceModal')"
                    class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded-md">Tutup</button>
                <button onclick="printInvoice()"
                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Cetak</button>
            </div>
        </div>
    </div>


    <!-- 🔹 Modal Konfirmasi Hapus -->
    <div id="confirmModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-80">
            <h3 class="text-lg font-bold mb-4">Konfirmasi</h3>
            <p id="confirmMessage" class="mb-4">Yakin hapus order ini?</p>
            <div class="flex justify-end gap-2">
                <button onclick="closeModal('confirmModal')" class="px-3 py-1 bg-gray-300 rounded">Batal</button>
                <button id="confirmBtn" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>

    <!-- 🔹 Notifikasi -->
    <div id="notif" class="fixed top-5 right-5 hidden bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50"></div>

    <script>
        function showNotif(message, type = 'success') {
            const notif = document.getElementById('notif');
            notif.innerText = message;
            notif.className =
                `fixed top-5 right-5 px-4 py-2 rounded shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notif.classList.remove('hidden');
            setTimeout(() => notif.classList.add('hidden'), 2500);
        }

        function openModal(id, orderId, shipStatus, payStatus, resiVal = '') {
            document.getElementById(id).classList.remove('hidden');
            if (id === 'updateModal') {
                document.getElementById('order_id').value = orderId;
                document.getElementById('shipping_status').value = shipStatus;
                document.getElementById('payment_status').value = payStatus;
                toggleResiField(shipStatus, payStatus, resiVal);
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function toggleResiField(shipStatus, payStatus, resiVal = '') {
            if (['shipped', 'delivered'].includes(shipStatus)) {
                document.getElementById('resiField').classList.remove('hidden');
                document.getElementById('nomor_resi').value = resiVal ?? '';
            } else {
                document.getElementById('resiField').classList.add('hidden');
                document.getElementById('nomor_resi').value = '';
            }
        }

        document.getElementById('shipping_status').addEventListener('change', function() {
            toggleResiField(this.value, document.getElementById('payment_status').value, document.getElementById(
                'nomor_resi').value);
        });
        document.getElementById('payment_status').addEventListener('change', function() {
            toggleResiField(document.getElementById('shipping_status').value, this.value, document.getElementById(
                'nomor_resi').value);
        });
        // ✅ Buka modal dengan data terisi
        function openUpdateModal(id, shipping_status, payment_status, nomor_resi) {
            document.getElementById('order_id').value = id;
            document.getElementById('shipping_status').value = shipping_status;
            document.getElementById('payment_status').value = payment_status;
            document.getElementById('nomor_resi').value = nomor_resi || '';

            // tampilkan field resi kalau status = shipped/delivered
            if (shipping_status === 'shipped' || shipping_status === 'delivered') {
                document.getElementById('resiField').classList.remove('hidden');
            } else {
                document.getElementById('resiField').classList.add('hidden');
            }

            document.getElementById('updateModal').classList.remove('hidden');
        }

        // ✅ Tutup modal
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // ✅ Tampilkan/hilangkan resi sesuai shipping_status
        document.getElementById('shipping_status').addEventListener('change', function() {
            if (this.value === 'shipped' || this.value === 'delivered') {
                document.getElementById('resiField').classList.remove('hidden');
            } else {
                document.getElementById('resiField').classList.add('hidden');
                document.getElementById('nomor_resi').value = '';
            }
        });

        // ✅ Submit form
        document.getElementById('updateForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = document.getElementById('order_id').value;
            const shipping_status = document.getElementById('shipping_status').value;
            const payment_status = document.getElementById('payment_status').value;
            const nomor_resi = document.getElementById('nomor_resi').value;

            let res = await fetch(`/pesanan/${id}`, {
                method: "POST", // ⚡ gunakan POST + _method PUT agar aman di Laravel
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    _method: "PUT",
                    shipping_status,
                    payment_status,
                    nomor_resi
                })
            });

            let data = await res.json();
            if (data.success) {
                showNotif(data.message || "Berhasil update order");
                setTimeout(() => location.reload(), 1200);
            } else {
                showNotif(data.message || "Gagal update order", 'error');
            }
        });

        function confirmDelete(id) {
            openModal('confirmModal');
            document.getElementById('confirmBtn').onclick = () => deleteOrder(id);
        }

        async function deleteOrder(id) {
            let res = await fetch(`/pesanan/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });

            let data = await res.json();
            closeModal('confirmModal');
            if (data.success) {
                showNotif("Order berhasil dihapus");
                setTimeout(() => location.reload(), 1200);
            } else {
                showNotif("Gagal hapus order", 'error');
            }
        }
    </script>

    <script>
        function escapeHtml(text) {
            if (typeof text !== "string") return text ?? '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function getKurirLogo(kurirName) {
            if (!kurirName) return null;
            const name = kurirName.toLowerCase();
            if (name.includes('j&t')) return '/image/jntexpresss.png';
            if (name.includes('jne')) return '/image/jne.png';
            if (name.includes('sicepat')) return '/image/sicepat.png';
            if (name.includes('wahana')) return '/image/wahana.png';
            return null;
        }

        // 🔹 Render Invoice
        function renderInvoiceHTML(data) {
            const order = data.order;
            const settings = data.settings;
            const kurirLogo = getKurirLogo(order.kurir?.name);

            // hitung total berat & jumlah
            const totalBerat = order.items?.reduce((sum, i) => sum + (i.product_berat * i.quantity), 0) ?? 0;
            const totalJumlah = order.items?.reduce((sum, i) => sum + i.quantity, 0) ?? 0;
            const isiPaket = order.items?.map(i =>
        `${escapeHtml(i.product_name)} (x${i.quantity})`
    ).join(", ") ?? "-";


            const shippingLabelSection = `
        <div id="shipping-label-section" class="p-6 border-2 border-black rounded-lg mb-4 print:border-black">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Label Pengiriman</h3>
                    <span class="text-sm text-gray-500">Kode Pesanan: ${escapeHtml(order.kode_pesanan)}</span>
                </div>
                ${kurirLogo
                    ? `<img src="${kurirLogo}" alt="Logo Kurir" class="h-10 w-auto object-contain">`
                    : `<span class="text-sm text-gray-500 font-bold">${escapeHtml(order.kurir?.name ?? '-')}</span>`}
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                <div>
                    <p class="font-semibold">Penerima:</p>
                    <p>${escapeHtml(order.user?.name)}</p>
                    <p>${escapeHtml(order.address?.alamat_lengkap ?? '-')}, ${escapeHtml(order.address?.provinsi ?? '-')}, ${escapeHtml(order.address?.kota ?? '-')}</p>
                    <p>Telp: ${escapeHtml(order.address?.telepon ?? '-')}</p>
                </div>
                <div>
                    <p class="font-semibold">Pengirim:</p>
                    <p>${escapeHtml(settings?.store_name ?? 'Nama Toko Anda')}</p>
                    <p>${escapeHtml(settings?.address ?? 'Jl. Raya No. 123, Jember')}</p>
                    <p>Telp: ${escapeHtml(settings?.phone ?? '(081) 234-5678')}</p>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 text-sm mt-6">
                <div><b>Layanan:</b> REGULER</div>
                <div><b>Berat:</b> ${totalBerat} Gram</div>
                <div><b>Jumlah:</b> ${totalJumlah}</div>
                <div><b>Isi Paket:</b> ${isiPaket} </div>
            </div>
            <div class="mt-2 text-sm">
                <b>Catatan:</b> ${order.catatan?.trim() ? escapeHtml(order.catatan) : '-'}
            </div>
        </div>`;

            const sellerArchiveSection = `
        <div id="seller-archive-section" class="p-4 border-2 border-black rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-sm text-gray-800">Arsip Penjual</h3>
                ${kurirLogo ? `<img src="${kurirLogo}" alt="Logo Kurir" class="h-8 w-auto object-contain">` : ''}
            </div>
            <div class="grid grid-cols-2 text-xs">
                <p><b>Order ID:</b> ${escapeHtml(order.kode_pesanan)}</p>
                <p><b>Tanggal:</b> ${new Date(order.created_at).toLocaleDateString('id-ID')}</p>
                <p><b>Penerima:</b> ${escapeHtml(order.user?.name)}</p>
                <p><b>Resi:</b> ${escapeHtml(order.nomor_resi ?? '-')}</p>
                <p><b>Layanan:</b> REGULER</p>
                <p><b>Berat:</b> ${totalBerat} Gram</p>
                <p><b>Jumlah:</b> ${totalJumlah}</p>
                <p><b>Isi Paket:</b> ${isiPaket} </p>
            </div>
            <div class="mt-2 text-xs">
                <b>Catatan:</b> ${order.catatan?.trim() ? escapeHtml(order.catatan) : '-'}
            </div>
        </div>`;

            return `
        <div class="space-y-4">
            ${shippingLabelSection}
            <div class="text-center font-bold print:hidden">----------------------------------------------------------------------------------------</div>
            ${sellerArchiveSection}
        </div>`;
        }

        // 🔹 Fetch & tampilkan invoice
        async function openInvoice(id) {
            openModal('invoiceModal');
            const invoiceContent = document.getElementById('invoiceContent');
            invoiceContent.innerHTML = "<p class='text-gray-500'>Loading...</p>";

            try {
                let res = await fetch(`/pesanan/${id}/invoice`);
                if (!res.ok) {
                    let text = await res.text();
                    throw new Error(`HTTP ${res.status} - ${text}`);
                }

                let order = await res.json();
                invoiceContent.innerHTML = renderInvoiceHTML(order);
            } catch (err) {
                invoiceContent.innerHTML =
                    "<p class='text-red-500'>Gagal memuat invoice. Lihat console untuk detail.</p>";
                console.error("❌ Error saat fetch invoice:", err);
            }
        }


        function printInvoice() {
            const invoiceHTML = document.getElementById('invoiceContent').innerHTML;
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            const htmlToPrint = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Cetak Label dan Arsip</title>
        <script src="https://cdn.tailwindcss.com"><\/script>
        <style>
            body { font-family: Arial, sans-serif; color: #000; }
            @page { size: A4; margin: 0; }
            @media print {
                body { margin: 0; padding: 10mm; }
                #shipping-label-section,
                #seller-archive-section {
                    border: 2px solid #000 !important;
                    padding: 10mm !important;
                    margin-bottom: 10mm;
                }
            }
        </style>
    </head>
    <body>
        ${invoiceHTML}
        <script>
            window.onload = function() {
                setTimeout(() => { window.print(); window.close(); }, 500);
            }
        <\/script>
    </body>
    </html>`;
            printWindow.document.open();
            printWindow.document.write(htmlToPrint);
            printWindow.document.close();
        }
    </script>



@endsection
