@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold text-gray-800">Daftar Dokter</h2>
        <a href="{{ route('admin.dokter.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
            + Tambah Dokter
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-left border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 border-b">Nama</th>
                    <th class="p-3 border-b">Spesialis</th>
                    <th class="p-3 border-b">Unit</th>
                    <th class="p-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokters as $dokter)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="p-3">{{ $dokter->nama }}</td>
                    <td class="p-3">{{ $dokter->spesialis }}</td>
                    <td class="p-3">{{ optional($dokter->unit)->nama_unit ?? '-' }}</td>
                    <td class="p-3 text-center flex justify-center gap-3">
                        <a href="{{ route('admin.dokter.edit', $dokter->id) }}" class="text-blue-600 hover:underline">Edit</a>

                        <button onclick="confirmDelete({{ $dokter->id }})" class="text-red-600 hover:underline">
                            Hapus
                        </button>

                        <form id="delete-form-{{ $dokter->id }}" action="{{ route('admin.dokter.destroy', $dokter->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <p class="text-lg font-semibold mb-4 text-gray-800">Yakin ingin menghapus dokter ini?</p>
        <div class="flex justify-center gap-4">
            <button onclick="hideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
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
