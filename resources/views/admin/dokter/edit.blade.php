@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Dokter</h2>

    <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST" id="dokterEditForm" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Dokter <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ $dokter->nama }}"
                   class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                   required>
        </div>

        <!-- Spesialis -->
        <div>
            <label for="spesialis" class="block text-sm font-medium text-gray-700">Spesialis</label>
            <input type="text" name="spesialis" id="spesialis" value="{{ $dokter->spesialis }}"
                   class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <!-- Unit Layanan -->
        <div>
            <label for="unit_layanan_id" class="block text-sm font-medium text-gray-700">Unit Layanan <span class="text-red-500">*</span></label>
            <select name="unit_layanan_id" id="unit_layanan_id" required
                    class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $unit->id == $dokter->unit_layanan_id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Update -->
        <div>
            <button type="submit"
                    id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 
                           rounded-md transition duration-200">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
