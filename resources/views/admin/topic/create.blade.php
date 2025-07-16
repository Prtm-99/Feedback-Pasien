@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 shadow-lg rounded-lg mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Topik</h2>

    <form action="{{ route('admin.topic.store') }}" method="POST" id="topikForm" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Topik <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" placeholder="Contoh: Pelayanan Dokter"
                   class="mt-1 w-full border border-gray-300 px-4 py-2 rounded-md shadow-sm 
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
        </div>

        <div>
            <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- JavaScript: Tombol loading state -->
<script>
    const form = document.getElementById('topikForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', () => {
        submitBtn.innerText = 'Menyimpan...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endsection
