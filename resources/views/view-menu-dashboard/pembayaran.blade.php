@extends('admin.main')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Pembayaran</h1>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full bg-white shadow rounded">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Pesanan</th>
                        <th class="px-4 py-2">Metode</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $index => $bayar)
                        <tr>
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $bayar->order->kode_pesanan }}</td>
                            <td class="border px-4 py-2">{{ $bayar->method->name }}</td>
                            <td class="border px-4 py-2">Rp {{ number_format($bayar->order->total_harga, 0, ',', '.') }}</td>
                            <td class="border px-4 py-2">{{ $bayar->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
