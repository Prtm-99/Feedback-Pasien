@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-bold mb-4">Edit Pertanyaan</h2>
    <form action="{{ route('admin.question.update', $question->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Topik</label>
            <select name="topic_id" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Topik --</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $question->topic_id == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Pertanyaan</label>
            <input type="text" 
                   name="text" 
                   class="w-full p-2 border rounded" 
                   value="{{ old('text', $question->text) }}" 
                   required 
                   placeholder="Contoh: Apakah dokter ramah?">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
