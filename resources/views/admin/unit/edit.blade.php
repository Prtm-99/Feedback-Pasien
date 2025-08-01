@extends('admin.layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Unit Layanan</h2>

    <form method="POST" action="{{ route('admin.unit.update', $unit->id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit</label>
            <input type="text" name="nama_unit" id="nama_unit"
                   value="{{ old('nama_unit', $unit->nama_unit) }}"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition" required>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3"
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">{{ old('deskripsi', $unit->deskripsi) }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
