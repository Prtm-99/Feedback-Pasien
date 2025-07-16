@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">📊 Ringkasan Feedback Pasien</h2>

        <form method="GET" class="flex gap-2 mb-4">
    <select name="bulan" class="border rounded px-3 py-2">
        <option value="">📅 Semua Bulan</option>
        @for ($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
        @endfor
    </select>

    <select name="tahun" class="border rounded px-3 py-2">
        <option value="">📆 Semua Tahun</option>
        @foreach ($availableYears as $year)
            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Filter
    </button>
</form>

        {{-- Ringkasan Skor per Identitas --}}
        <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nama Pasien</th>
                        <th class="px-4 py-3">Dokter</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3 text-center">Jumlah Feedback</th>
                        <th class="px-4 py-3 text-center">Skor Total</th>
                        <th class="px-4 py-3 text-center">Rata-rata</th>
                        <th class="px-4 py-3 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summary as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $item['identitas']->nama ?? '-' }}</td>
                            <td class="px-4 py-2">{{ optional($item['identitas']->dokter)->nama ?? '-' }}</td>
                            <td class="px-4 py-2">{{ optional($item['identitas']->unit)->nama_unit ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $item['feedback_count'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $item['score'] }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($item['ikm'], 2, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center font-semibold">
                                <span class="px-2 py-1 rounded 
                                    @if($item['kategori'] == 'Sangat Baik') bg-green-500 
                                    @elseif($item['kategori'] == 'Baik') bg-blue-500 
                                    @elseif($item['kategori'] == 'Kurang') bg-yellow-500 
                                    @else bg-red-500 
                                    @endif text-white">
                                    {{ $item['kategori'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                Tidak ada data feedback ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

{{-- Semua Pertanyaan & Feedback dikelompokkan berdasarkan identitas --}}
<div class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden mt-6">
    <h3 class="text-lg font-semibold px-6 py-4 border-b border-gray-200 dark:border-gray-700">📌 Daftar Pertanyaan dan Feedback per Pasien</h3>
    <div class="overflow-x-auto px-6 pb-6">

        @forelse($feedbacksByIdentitas as $identitasId => $groupedFeedbacks)
            @php $pasien = $groupedFeedbacks->first()->identitas ?? null; @endphp

            <div class="mb-4">
                <h4 class="text-md font-bold text-blue-700 dark:text-blue-300 mb-2">
                    🧍 Nama: {{ $pasien->nama ?? '-' }} | Dokter: {{ $pasien->dokter->nama ?? '-' }} | Unit: {{ $pasien->unit->nama_unit ?? '-' }}
                </h4>

                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300 border mb-4">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-4 py-2">Topik</th>
                            <th class="px-4 py-2">Pertanyaan</th>
                            <th class="px-4 py-2">Jawaban</th>
                            <th class="px-4 py-2">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedFeedbacks as $fb)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-2">{{ $fb->topic->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $fb->question->text ?? $fb->pertanyaan_statis ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $fb->answer }}</td>
                                <td class="px-4 py-2">{{ $fb->comment ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400 py-4 text-center">Belum ada feedback yang tersedia.</p>
        @endforelse

    </div>
</div>

        </div>
    </div>
</div>
@endsection
