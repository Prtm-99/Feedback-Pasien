@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Daftar Pertanyaan</h2>
        <a href="{{ route('admin.question.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Tambah Pertanyaan
        </a>
    </div>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100 text-gray-600 text-left">
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">Topik</th>
                <th class="py-2 px-4 border-b">Pertanyaan</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $index => $question)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                    <td class="py-2 px-4 border-b">{{ $question->topic->name ?? '-' }}</td>
                    <td class="py-2 px-4 border-b">{{ $question->text }}</td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <a href="{{ route('admin.question.edit', $question->id) }}"
                           class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.question.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline"
                                    onclick="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

            @if($questions->isEmpty())
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data pertanyaan</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
