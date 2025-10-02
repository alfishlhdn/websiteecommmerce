@extends('admin.main')
@section('judul', 'Riwayat Transaksi | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Riwayat Transaksi</h1>

        <!-- 🔎 Filter -->
        <form method="GET" action="{{ route('riwayat-transaksi.index') }}" class="mb-4 flex gap-3 flex-wrap">
            <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded">
            <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded">

            <select name="shipping_status" class="border p-2 rounded">
                <option value="">-- Semua Status Pengiriman --</option>
                <option value="pending" {{ request('shipping_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('shipping_status') == 'processing' ? 'selected' : '' }}>Proses</option>
                <option value="shipped" {{ request('shipping_status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                <option value="delivered" {{ request('shipping_status') == 'delivered' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('shipping_status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
            <a href="{{ route('riwayat-transaksi.index') }}" class="px-4 py-2 bg-gray-300 rounded">Reset</a>
        </form>

        <!-- 🔹 Tabel Riwayat -->
        <div class="mt-4 overflow-x-auto">
            <table class="w-full bg-white shadow rounded text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Kode Pesanan</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Catatan</th>
                        <th class="px-4 py-2">Kurir</th>
                        <th class="px-4 py-2">Resi</th>
                        <th class="px-4 py-2">Pembayaran</th>
                        <th class="px-4 py-2">Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $r)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border px-4 py-2">{{ $orders->firstItem() + $index }}</td>
                            <td class="border px-4 py-2">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td class="border px-4 py-2 font-semibold">{{ $r->kode_pesanan }}</td>
                            <td class="border px-4 py-2">{{ $r->user->name ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                <ul class="list-disc pl-4">
                                    @foreach ($r->items as $item)
                                        <li>{{ $item->nama_produk ?? '-' }} ({{ $item->harga }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border px-4 py-2">{{ $r->items->sum('jumlah') }}x</td>
                            <td class="border px-4 py-2 text-green-600 font-bold">
                                Rp {{ number_format($r->total, 0, ',', '.') }}
                            </td>
                            <td class="border px-4 py-2">{{ $r->catatan ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $r->kurir->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $r->nomor_resi ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded
                            {{ $r->payment_status == 'paid'
                                ? 'bg-green-100 text-green-700'
                                : ($r->payment_status == 'pending'
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($r->payment_status ?? '-') }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded
                            {{ $r->shipping_status == 'selesai'
                                ? 'bg-green-100 text-green-700'
                                : ($r->shipping_status == 'pending'
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : ($r->shipping_status == 'proses'
                                        ? 'bg-blue-100 text-blue-700'
                                        : ($r->shipping_status == 'dikirim'
                                            ? 'bg-purple-100 text-purple-700'
                                            : 'bg-red-100 text-red-700'))) }}">
                                    {{ ucfirst($r->shipping_status ?? '-') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
