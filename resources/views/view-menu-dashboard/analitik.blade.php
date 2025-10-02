@extends('admin.main')
@section('judul', 'Analitik Pengunjung | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Analitik Pengunjung</h1>

        {{-- Ringkasan Pengunjung --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-gray-600 text-sm">Total Pengunjung</p>
                <h2 class="text-2xl font-bold text-blue-600">{{ number_format($totalVisitors) }}</h2>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-gray-600 text-sm">Pengunjung Hari Ini</p>
                <h2 class="text-2xl font-bold text-green-600">{{ $todayVisitors }}</h2>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-gray-600 text-sm">Pengunjung Minggu Ini</p>
                <h2 class="text-2xl font-bold text-yellow-600">{{ $weeklyVisitors }}</h2>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <p class="text-gray-600 text-sm">Pengunjung Bulan Ini</p>
                <h2 class="text-2xl font-bold text-purple-600">{{ $monthlyVisitors }}</h2>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Grafik Pengunjung (7 Hari Terakhir)</h2>
            <canvas id="visitorChart" class="w-full h-64"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('visitorChart').getContext('2d');
            const visitorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Jumlah Pengunjung',
                        data: @json($chartData),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
