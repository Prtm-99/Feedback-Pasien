{{-- resources/views/admin/unit/dokter.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            👨‍⚕️ Dokter di Unit: {{ $unit->nama_unit }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        @forelse($unit->dokters as $dokter)
            <a href="{{ route('admin.identitas.create', $dokter->id) }}"
               class="block group">
                <div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                            <i class="fas fa-user-md fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600">
                                {{ $dokter->nama }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Spesialis: {{ $dokter->spesialis ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-300 text-lg">
                    Belum ada dokter pada unit ini.
                </p>
            </div>
        @endforelse
    </div>
</x-app-layout>
