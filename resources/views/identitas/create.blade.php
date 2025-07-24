<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            📝 Formulir Identitas
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-gray-50 border border-blue-200 p-8 rounded-2xl shadow-xl transition duration-300">
            <form action="{{ route('admin.identitas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                {{-- Jika ingin pakai default unit --}}
                <input type="hidden" name="unit_layanan_id" value="1">

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx"
                        class="mt-1 w-full rounded-lg border border-blue-300 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-400 shadow-sm">
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required
                        class="mt-1 w-full rounded-lg border border-blue-300 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-400 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                {{-- Usia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Usia</label>
                    <input type="number" name="usia" required placeholder="Contoh: 30"
                        class="mt-1 w-full rounded-lg border border-blue-300 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-400 shadow-sm">
                </div>

                {{-- Pendidikan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pendidikan</label>
                    <input type="text" name="pendidikan" required placeholder="Contoh: S1"
                        class="mt-1 w-full rounded-lg border border-blue-300 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-400 shadow-sm">
                </div>

                {{-- Pekerjaan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input type="text" name="pekerjaan" required placeholder="Contoh: Guru"
                        class="mt-1 w-full rounded-lg border border-blue-300 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-400 shadow-sm">
                </div>

                {{-- Tombol Submit --}}
                <div class="md:col-span-2 text-right mt-4">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
