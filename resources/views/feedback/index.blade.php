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

        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-black border border-gray-200">
            <thead class="bg-blue-100 text-gray-800 uppercase text-xs font-bold sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Unit</th>
                    <th class="px-4 py-3 border">No Hp</th>
                    <th class="px-4 py-3 text-center border">Feedback</th>
                    <th class="px-4 py-3 text-center border">Rata-rata</th>
                    <th class="px-4 py-3 text-center border">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $index => $item)
                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-200 cursor-pointer"
                        onclick="toggleDetail('detail-{{ $index }}')">
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ optional($item['identitas']->unit)->nama_unit ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $item['identitas']->no_hp }}</td>
                        <td class="px-4 py-2 text-center border">{{ $item['feedback_count'] }}</td>
                        <td class="px-4 py-2 text-center border">{{ number_format($item['ikm'], 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center font-semibold border">
                            <span class="px-2 py-1 rounded text-white
                                @if($item['ikm'] >= 85)
                                    bg-green-500
                                @elseif($item['ikm'] >= 70)
                                    bg-yellow-400 text-gray-900
                                @else
                                    bg-red-500
                                @endif">
                                {{ $item['kategori'] }}
                            </span>
                        </td>
                    </tr>
                    <tr id="detail-{{ $index }}" class="hidden bg-gray-50">
                        <td colspan="7" class="px-4 py-2 border">
                            <table class="w-full text-sm mt-2 border border-gray-200 rounded">
                                <thead class="bg-blue-100 text-black uppercase text-xs font-bold rounded">
                                    <tr>
                                        <th class="px-2 py-1 border border-gray-300">Topik</th>
                                        <th class="px-2 py-1 border border-gray-300">Pertanyaan</th>
                                        <th class="px-2 py-1 border border-gray-300">Jawaban</th>
                                        <th class="px-2 py-1 border border-gray-300">Komentar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacksByIdentitas[$item['identitas']->id] ?? [] as $fb)
                                        <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-2 py-1 border border-gray-300">{{ $fb->topic->name ?? '-' }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $fb->question->text ?? $fb->pertanyaan_statis ?? '-' }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $fb->answer }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $fb->comment ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-black py-6">
                            Tidak ada data feedback ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-blue-100 border-t border-gray-200 shadow-sm rounded-b">
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
