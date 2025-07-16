@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Unit Layanan</h2>
        <a href="{{ route('admin.unit.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
            + Tambah Unit
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-100 text-left text-gray-700">
                <tr>
                    <th class="p-3">Nama Unit</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $unit->nama_unit }}</td>
                    <td class="p-3">{{ $unit->deskripsi }}</td>
                    <td class="p-3 text-center flex justify-center gap-2">
                        <a href="{{ route('admin.unit.edit', $unit->id) }}" 
                           class="text-blue-600 hover:underline">Edit</a>

                        <button onclick="confirmDelete({{ $unit->id }})" 
                                class="text-red-600 hover:underline">
                            Hapus
                        </button>

                        <!-- Hidden delete form -->
                        <form id="delete-form-{{ $unit->id }}" 
                              action="{{ route('admin.unit.destroy', $unit->id) }}" 
                              method="POST" class="hidden">
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

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <p class="text-lg font-semibold mb-4">Yakin ingin menghapus unit ini?</p>
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
