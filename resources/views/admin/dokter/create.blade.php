@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Tambah Dokter</h2>

    <form action="{{ route('admin.dokter.store') }}" method="POST" id="dokterForm" class="space-y-5">
        @csrf

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Dokter <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" 
                   class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                   required>
        </div>

        <!-- Spesialis -->
        <div>
            <label for="spesialis" class="block text-sm font-medium text-gray-700">Spesialis</label>
            <input type="text" name="spesialis" id="spesialis"
                   class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <!-- Unit Layanan -->
        <div>
            <label for="unit_layanan_id" class="block text-sm font-medium text-gray-700">Unit Layanan <span class="text-red-500">*</span></label>
            <select name="unit_layanan_id" id="unit_layanan_id" required
                    class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol -->
        <div>
            <button type="submit" 
                    class="w-full bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 px-4 
                           rounded-md transition duration-200" 
                    id="submitBtn">
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- Optional Script: loading saat submit -->
<script>
    const form = document.getElementById('dokterForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', () => {
        submitBtn.innerText = 'Menyimpan...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endsection
