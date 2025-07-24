@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📊 Admin Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card: Unit -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg hover:scale-105 transition">
            <div class="flex items-center space-x-4">
                <div class="text-blue-500 text-3xl"><i class="fas fa-hospital-alt"></i></div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-600">Jumlah Unit Layanan</h2>
                    <p class="text-xl font-bold text-blue-700">{{ $units }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Pasien dan Filter -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg hover:scale-105 transition">
            <div class="flex items-center space-x-4">
                <div class="text-pink-500 text-3xl"><i class="fas fa-users"></i></div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-600">Pasien Mengisi</h2>
                    <p class="text-xl font-bold text-pink-700">{{ $identitas->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Card: IKM -->
        <div class="bg-white rounded-xl shadow-md p-6 col-span-1 hover:shadow-lg hover:scale-105 transition">
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <select name="bulan" class="border rounded px-3 py-2">
                        <option value="">📅 Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>

                    <select name="filter_tahun" class="border rounded px-3 py-2">
                        <option value="">📅 Semua Tahun</option>
                        @foreach ($availableYears as $tahun)
                            <option value="{{ $tahun }}" {{ $filterTahun == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Filter
                    </button>
                </div>
            </form>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Rata-rata IKM</h2>
            <div class="relative pt-1">
                <div class="overflow-hidden h-4 mb-4 text-xs flex rounded bg-blue-200">
                    <div style="width: {{ $ikmScore }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-700">
                        {{ $ikmScore }} / 100
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Feedback dan IKM -->
    <div class="bg-white rounded-xl shadow-md p-6 mt-8 hover:shadow-lg transition">
            <button onclick="downloadChart('feedbackChart', 'feedback-pasien.png')" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                📥 Download
            </button>
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Feedback Pasien per Bulan</h2>
                <canvas id="feedbackChart" height="100"></canvas>
            </div>
            <div>
            <button onclick="downloadChart('ikmChart', 'ikm-skor.png')" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                📥 Download
            </button>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Peningkatan / Penurunan Skor IKM per Bulan</h2>
                <canvas id="ikmChart" height="100"></canvas>
            </div>
    </div>
</div>

<!-- FontAwesome CDN -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const feedbackLabels = {!! json_encode($labels) !!};
    const feedbackData   = {!! json_encode($data) !!};
    const ikmLabels     = {!! json_encode($ikmLabels) !!};
    const ikmData       = {!! json_encode($ikmData) !!};

    function downloadChart(canvasId, filename) {
    const link = document.createElement('a');
    link.href = document.getElementById(canvasId).toDataURL('image/png');
    link.download = filename;
    link.click();
}

    // Feedback chart
    const ctxFeedback = document.getElementById('feedbackChart').getContext('2d');
    new Chart(ctxFeedback, {
        type: 'bar',
        data: {
            labels: feedbackLabels,
            datasets: [{
                label: 'Jumlah Feedback',
                data: feedbackData,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#f9fafb',
                    titleColor: '#111827',
                    bodyColor: '#1f2937',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // IKM chart
    const ctxIkm = document.getElementById('ikmChart').getContext('2d');
    new Chart(ctxIkm, {
        type: 'line',
        data: {
            labels: ikmLabels,
            datasets: [{
                label: 'Rata-rata IKM',
                data: ikmData,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(5, 150, 105, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    backgroundColor: '#f9fafb',
                    titleColor: '#111827',
                    bodyColor: '#1f2937',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endsection
