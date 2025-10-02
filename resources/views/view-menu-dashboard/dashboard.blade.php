@extends('admin.main')

@section('judul', 'Dashboard | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        {{-- Judul --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Dashboard Admin</h1>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            {{-- Card --}}
            @php
                $cards = [
                    [
                        'label' => 'Total Produk',
                        'value' => $totalProduk,
                        'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                        'bg' => 'bg-blue-500',
                    ],
                    [
                        'label' => 'Kategori Produk',
                        'value' => $totalKategori,
                        'icon' =>
                            'M7 7h.01M7 3h.01M11 7h.01M11 3h.01M15 7h.01M15 3h.01M19 7h.01M19 3h.01M12 2v20M6 12h12',
                        'bg' => 'bg-green-500',
                    ],
                    [
                        'label' => 'Pesanan',
                        'value' => $totalPesanan,
                        'icon' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'bg' => 'bg-yellow-500',
                    ],
                    [
                        'label' => 'Total Pembayaran',
                        'value' => 'Rp ' . number_format($totalPembayaran, 0, ',', '.'),
                        'icon' =>
                            'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 6v2m0 6a9 9 0 110-18 9 9 0 010 18z',
                        'bg' => 'bg-red-500',
                    ],
                    [
                        'label' => 'Pelanggan',
                        'value' => $totalPelanggan,
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'bg' => 'bg-purple-500',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-3 text-white rounded-full {{ $card['bg'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Grafik & Aktivitas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Grafik Penjualan --}}
            <div class="bg-white p-6 rounded-xl shadow-md h-80 hover:shadow-lg transition-shadow duration-200">
                <p class="text-lg font-semibold text-gray-800 mb-4">Penjualan 7 Hari Terakhir</p>
                <div class="h-full rounded-lg overflow-hidden border border-gray-200">
                    <canvas id="penjualanChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Aktivitas Terbaru --}}
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200">
                <p class="text-lg font-semibold text-gray-800 mb-4">Ulasan & Komentar Terbaru</p>
                <ul
                    class="space-y-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @forelse ($komentarTerbaru as $komentar)
                        <li class="flex items-start space-x-3 border-b pb-3 last:border-b-0">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $komentar->user->name ?? 'Pengguna' }}</p>
                                <p class="text-sm text-gray-600 italic">"{{ $komentar->komentar }}"</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $komentar->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-gray-500 py-4">Tidak ada komentar terbaru.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Tabel Pesanan Terbaru --}}
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-center mb-4">
                <p class="text-lg font-semibold text-gray-800">Pesanan Terbaru</p>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm table-auto border-collapse">
                    <thead>
                        <tr class="text-left bg-gray-50 text-gray-700">
                            <th class="py-3 px-6 font-medium">No</th>
                            <th class="py-3 px-6 font-medium">Nama Pelanggan</th>
                            <th class="py-3 px-6 font-medium">Produk</th>
                            <th class="py-3 px-6 font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesananTerbaru as $index => $pesanan)
                            <tr class="border-b last:border-b-0 hover:bg-gray-50 transition-colors duration-150">
                                <td class="py-3 px-6">{{ $index + 1 }}</td>
                                <td class="py-3 px-6">{{ $pesanan->user->name ?? 'N/A' }}</td>
                                <td class="py-3 px-6">
                                    {{ $pesanan->items->pluck('product.nama_produk')->join(', ') ?: 'N/A' }}</td>
                                <td class="py-3 px-6 font-medium text-gray-900">Rp
                                    {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                <td class="py-3 px-6">
                                    @php
                                        $statusClass = [
                                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'diproses' => 'bg-blue-100 text-blue-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            'dibatalkan' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">Tidak ada pesanan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartLabels = @json($chartLabels ?? []);
            const chartData = @json($chartData ?? []);
            const ctx = document.getElementById('penjualanChart').getContext('2d');

            const emptyDataPlugin = {
                id: 'emptyDataPlugin',
                afterDraw: chart => {
                    const data = chart.data.datasets[0]?.data || [];
                    if (data.every(v => v === 0)) {
                        const {
                            ctx,
                            chartArea: {
                                left,
                                top,
                                width,
                                height
                            }
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 16px sans-serif';
                        ctx.fillStyle = '#9ca3af';
                        ctx.fillText('Tidak ada data penjualan', left + width / 2, top + height / 2);
                        ctx.restore();
                    }
                }
            };

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: chartData,
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 6,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: context => 'Total: Rp ' + context.raw.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => 'Rp ' + v.toLocaleString('id-ID'),
                                color: '#4b5563'
                            },
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#4b5563'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                },
                plugins: [emptyDataPlugin]
            });
        });
    </script>
@endsection
