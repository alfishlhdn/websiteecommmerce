@extends('admin.main')
@section('judul', 'Laporan Penjualan | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen mt-6">
        {{-- Header dan Filter Tanggal --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i> Laporan Penjualan
            </h1>
            <form class="flex items-center space-x-2 bg-white p-3 rounded-lg shadow-sm border border-gray-200" method="GET"
                action="{{ route('laporan.penjualan') }}">
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" />
                <span class="text-gray-500 font-semibold mx-1">sampai</span>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" />
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </form>
        </div>

        <div class="border-t border-gray-300 my-6"></div>

        {{-- Tabel Laporan Penjualan --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Tanggal
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Kode
                                Pesanan</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Pelanggan
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $i => $order)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-blue-600">{{ $order->kode_pesanan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name ?? 'Anonim' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                @if ($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800 @endif
                                ">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data penjualan
                                    dalam rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Ringkasan dan Export --}}
        <div
            class="mt-6 flex flex-col md:flex-row items-center justify-between p-5 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="mb-4 md:mb-0 text-gray-700 text-md font-semibold">
                Total Penjualan: <strong class="text-2xl text-blue-600 ml-2">Rp
                    {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
            </div>
            <a href="{{ route('laporan.penjualan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                <i class="fas fa-file-excel mr-2"></i> Export ke Excel
            </a>
        </div>
    </div>
@endsection
