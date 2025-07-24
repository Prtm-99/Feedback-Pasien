@extends('admin.layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white leading-tight">
            📋 Data Identitas Pasien
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div id="alert-success" class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-md transition-opacity duration-500 ease-in-out opacity-0">
                    ✅ {{ session('success') }}
                </div>
            @endif

                    {{-- Dropdown Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('admin.identitas.index') }}" class="mb-4 flex items-center gap-2">
            <select name="bulan" class="border px-3 py-2 rounded">
                <option value="">📅 Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="border px-3 py-2 rounded">
                <option value="">📆 Semua Tahun</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                🔍 Filter
            </button>
        </form>

            <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase font-bold text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">No HP</th>
                                <th class="px-4 py-3">Usia</th>
                                <th class="px-4 py-3">Gender</th>
                                <th class="px-4 py-3">Pendidikan</th>
                                <th class="px-4 py-3">Pekerjaan</th>
                                <th class="px-4 py-3">Unit</th>
                                <th class="px-4 py-3">Tgl Survei</th>
                                <th class="px-4 py-3">Jam</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($identitasList as $index => $identitas)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $identitas->no_hp }}</td>
                                    <td class="px-4 py-2">{{ $identitas->usia }}</td>                                    <td class="px-4 py-2">{{ $identitas->alamat }}</td>
                                    <td class="px-4 py-2">{{ $identitas->jenis_kelamin }}</td>
                                    <td class="px-4 py-2">{{ $identitas->pendidikan }}</td>
                                    <td class="px-4 py-2">{{ $identitas->pekerjaan }}</td>
                                    <td class="px-4 py-2">{{ $identitas->unit->nama_unit ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $identitas->tanggal_survei }}</td>
                                    <td class="px-4 py-2">{{ $identitas->jam_survei }}</td>
                                    <td class="px-4 py-2 flex gap-2 justify-center">
                                        <a href="{{ route('admin.identitas.edit', $identitas->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow">
                                            Edit
                                        </a>
                                        <form id="delete-form-{{ $identitas->id }}" action="{{ route('admin.identitas.destroy', $identitas->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $identitas->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-gray-500 dark:text-gray-400 py-6">
                                        Tidak ada data identitas tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
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

    {{-- JS: Fade Alert & Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alertBox = document.getElementById('alert-success');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.remove('opacity-0');
                    alertBox.classList.add('opacity-100');
                }, 100);
                setTimeout(() => {
                    alertBox.classList.remove('opacity-100');
                    alertBox.classList.add('opacity-0');
                }, 3000);
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

    <style>
        #alert-success {
            transition: opacity 0.5s ease-in-out;
        }
        .opacity-0 {
            opacity: 0;
        }
        .opacity-100 {
            opacity: 1;
        }
    </style>
@endsection
