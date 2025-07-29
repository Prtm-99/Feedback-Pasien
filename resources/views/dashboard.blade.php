<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight tracking-wide">
            🎯 Selamat Datang di Dashboard
        </h2>
        <p class="text-gray-600 mt-1 text-sm">
            Pilih unit layanan untuk mulai survei atau mengelola data.
        </p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative animate-pulse" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-800">🧭 Unit Layanan Tersedia</h3>
            </div>

            @if(isset($units) && $units->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($units as $unit)
                        <a href="{{ route('identitas.create', $unit->id) }}"
                           class="group relative block rounded-2xl overflow-hidden shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl bg-white ring-1 ring-gray-200 hover:ring-green-500 hover:ring-2">

                            {{-- Gradient Background Effect --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-green-300 to-emerald-400 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>

                            <div class="p-6 relative z-10 flex flex-col items-center text-center">
                                {{-- Icon --}}
                                <div class="bg-green-100 text-green-600 rounded-full p-4 shadow mb-4 transition-transform duration-300 group-hover:rotate-12">
                                    <i class="fas fa-hospital-user fa-2x"></i>
                                </div>

                                {{-- Nama Unit --}}
                                <h4 class="text-lg font-semibold text-gray-800 leading-tight">{{ $unit->nama_unit }}</h4>

                                {{-- Deskripsi --}}
                                @if($unit->deskripsi)
                                    <p class="text-sm mt-2 text-gray-600">{{ $unit->deskripsi }}</p>
                                @endif

                                {{-- CTA --}}
                                <span class="mt-4 text-sm font-semibold text-green-600 group-hover:underline transition-all">Mulai Survei</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else 
                <div class="mt-6 text-gray-500 text-center">
                    Belum ada unit layanan yang tersedia.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
