@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <h2 class="text-2xl font-bold text-black mb-4">Ringkasan Feedback Pasien</h2>

        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <select name="bulan" class="border rounded px-3 py-2">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="border rounded px-3 py-2">
                <option value="">Semua Tahun</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">
                Filter
            </button>
                <a href="{{ route('admin.feedback.export.excel', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow">
                Download Excel
                </a>
        </form>

        <div class="bg-white dark:bg-gray-900 shadow rounded-lg">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 text-xs font-bold">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">No Hp</th>
                        <th class="px-4 py-3 text-center">Feedback</th>
                        <th class="px-4 py-3 text-center">Rata-rata</th>
                        <th class="px-4 py-3 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summary as $index => $item)
                        <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="toggleDetail('detail-{{ $index }}')">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ optional($item['identitas']->unit)->nama_unit ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item['identitas']->no_hp}}</td>
                            <td class="px-4 py-2 text-center">{{ $item['feedback_count'] }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($item['ikm'], 2, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center font-semibold">
                                <span class="px-2 py-1 rounded text-white
                                    @if($item['ikm'] >= 85)
                                        bg-green-500
                                    @elseif($item['ikm'] >= 70)
                                        bg-yellow-500
                                    @else
                                        bg-red-500
                                    @endif">
                                    {{ $item['kategori'] }}
                                </span>
                            </td>
                        </tr>
                        <tr id="detail-{{ $index }}" class="hidden bg-gray-50 dark:bg-gray-800">
                            <td colspan="7" class="px-4 py-2">
                                <table class="w-full text-sm mt-2 border">
                                    <thead class="bg-gray-200 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-2 py-1">Topik</th>
                                            <th class="px-2 py-1">Pertanyaan</th>
                                            <th class="px-2 py-1">Jawaban</th>
                                            <th class="px-2 py-1">Komentar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($feedbacksByIdentitas[$item['identitas']->id] ?? [] as $fb)
                                            <tr>
                                                <td class="px-2 py-1">{{ $fb->topic->name ?? '-' }}</td>
                                                <td class="px-2 py-1">{{ $fb->question->text ?? $fb->pertanyaan_statis ?? '-' }}</td>
                                                <td class="px-2 py-1">{{ $fb->answer }}</td>
                                                <td class="px-2 py-1">{{ $fb->comment ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                Tidak ada data feedback ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-4">
                {{ $identitasList->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDetail(id) {
        const el = document.getElementById(id);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endsection
