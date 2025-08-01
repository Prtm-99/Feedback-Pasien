@extends('admin.layouts.app')

@section('content')
<x-slot name="header">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white leading-tight flex items-center gap-2">
        📋 Data Identitas Pasien
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- ✅ Alert Success --}}
        @if(session('success'))
            <div id="alert-success" class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-md transition-all duration-500 opacity-0 flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('admin.identitas.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
            <select name="bulan" class="border px-3 py-2 rounded shadow-sm">
                <option value="">📅 Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="border px-3 py-2 rounded shadow-sm">
                <option value="">📆 Semua Tahun</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                🔍 Filter
            </button>
        </form>

                {{-- ✅ Tabel Data --}}
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-black border border-gray-200">
                    <thead class="bg-blue-100 text-gray-800 uppercase text-xs font-bold sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 border">#</th>
                            <th class="px-4 py-3 border">No HP</th>
                            <th class="px-4 py-3 border">Usia</th>
                            <th class="px-4 py-3 border">Gender</th>
                            <th class="px-4 py-3 border">Pendidikan</th>
                            <th class="px-4 py-3 border">Pekerjaan</th>
                            <th class="px-4 py-3 border">Unit</th>
                            <th class="px-4 py-3 border">Tgl Survei</th>
                            <th class="px-4 py-3 border">Jam</th>
                            <th class="px-4 py-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($identitasList as $index => $identitas)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-200">
                                <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->no_hp }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->usia }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->jenis_kelamin }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->pendidikan }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->pekerjaan }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->unit->nama_unit ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->tanggal_survei }}</td>
                                <td class="px-4 py-2 border">{{ $identitas->jam_survei }}</td>
                                <td class="px-4 py-2 border flex gap-2 justify-center">
                                    <form id="delete-form-{{ $identitas->id }}" action="{{ route('admin.identitas.destroy', $identitas->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete({{ $identitas->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg shadow transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-gray-500 py-6">
                                    Tidak ada data identitas tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-blue-100 border-t border-gray-200 shadow-sm text-black">
                {{ $identitasList->links() }}
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <p class="text-lg font-semibold mb-4 text-gray-800">Yakin ingin menghapus data ini?</p>
        <div class="flex justify-center gap-4">
            <button onclick="hideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alertBox = document.getElementById('alert-success');
        if (alertBox) {
            setTimeout(() => alertBox.classList.add('opacity-100'), 100);
            setTimeout(() => alertBox.classList.remove('opacity-100'), 3000);
        }
    });

    let deleteFormId = null;
    

    function confirmDelete(id) {
        deleteFormId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
        if (deleteFormId) {
            document.getElementById(`delete-form-${deleteFormId}`).submit();
        }
    });
</script>
@endsection
