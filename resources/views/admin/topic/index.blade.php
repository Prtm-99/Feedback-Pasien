@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-black">Daftar Topik</h2>
        <a href="{{ route('admin.topic.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            + Tambah Topik
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
            <thead class="bg-blue-100 text-black">
                <tr>
                    <th class="py-3 px-4 text-left border-b">#</th>
                    <th class="py-3 px-4 text-left border-b">Nama Topik</th>
                    <th class="py-3 px-4 text-center border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topics as $index => $topic)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $topic->name }}</td>
                        <td class="py-3 px-4 text-center space-x-3">
                            <a href="{{ route('admin.topic.edit', $topic->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>

                            <<button onclick="confirmDelete(@json($topic->id))"
                                    class="text-red-600 hover:underline">
                                Hapus
                            </button>

                            <form id="delete-form-{{ $topic->id }}"
                                  action="{{ route('admin.topic.destroy', $topic->id) }}"
                                  method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-5 text-center text-gray-500">Tidak ada data topik.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md text-center">
        <p class="text-lg font-semibold text-gray-800 mb-4">Yakin ingin menghapus topik ini?</p>
        <div class="flex justify-center gap-4">
            <button onclick="hideModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded transition">
                Batal
            </button>
            <button id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded transition">
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
        document.getElementById('deleteModal').classList.add('flex');
    }

    function hideModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
        if (deleteFormId) {
            document.getElementById(`delete-form-${deleteFormId}`).submit();
        }
    });
</script>
@endsection
