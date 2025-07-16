<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">📋 Daftar Unit Layanan</h3>
            </div>

            @if(isset($units) && $units->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($units as $unit)
                        <a href="{{ route('admin.dokter.show', $unit->id) }}"
                           class="group block rounded-xl overflow-hidden shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-green-500 to-green-600">
                            <div class="p-6 flex flex-col items-center text-center text-white h-full">
                                <div class="bg-white text-green-600 rounded-full p-4 shadow-md mb-4 transition transform group-hover:rotate-6">
                                    <i class="fas fa-hospital fa-2x"></i>
                                </div>
                                <h4 class="text-lg font-semibold leading-tight">{{ $unit->nama_unit }}</h4>
                                @if($unit->deskripsi)
                                    <p class="text-sm mt-2 text-white/90">{{ $unit->deskripsi }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else 
                <div class="mt-6 text-gray-500 dark:text-gray-300 text-center">
                    Belum ada unit layanan yang tersedia.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
