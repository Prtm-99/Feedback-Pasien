@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Tambah Unit Layanan</h2>

    <form action="{{ route('admin.unit.store') }}" method="POST" id="unitForm" class="space-y-5">
        @csrf
        <div>
            <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit <span class="text-red-500">*</span></label>
            <input type="text" name="nama_unit" id="nama_unit"
                class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                required>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
            <textarea name="deskripsi" id="deskripsi" rows="3"
                class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
            Simpan
        </button>
    </form>

    <!-- Alert sukses -->
    <div id="successAlert" class="hidden mt-6 bg-green-100 text-green-800 px-4 py-3 rounded shadow">
        Data berhasil disimpan!
    </div>
</div>

<script>
    const form = document.getElementById('unitForm');
    const successAlert = document.getElementById('successAlert');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); 
        const originalButtonText = form.querySelector('button').innerHTML;
        form.querySelector('button').innerHTML = 'Menyimpan...';

        setTimeout(() => {
            form.submit();
        }, 500);
    });

    
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            successAlert.classList.remove('hidden');
        });
    @endif
</script>
@endsection
