<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-white leading-tight tracking-wide drop-shadow-lg">
            🎯 Selamat Datang di Dashboard
        </h2>
        <p class="text-blue-100 mt-1 text-sm">
            Pilih unit layanan untuk mulai survei atau mengelola data.
        </p>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-blue-200 via-blue-100 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="flex items-center bg-blue-100 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded-lg shadow animate-fade-in" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">🧭 Pilih Unit Layanan</h3>
            </div>

            @if(isset($units) && $units->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($units as $unit)
                        <a href="{{ route('identitas.create', $unit->id) }}"
                           class="group relative block rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transform hover:-translate-y-1 bg-white ring-1 ring-gray-200 hover:ring-blue-400 hover:ring-2 transition-all duration-300">

                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-300 to-blue-400 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>

                            <div class="p-6 relative z-10 flex flex-col items-center text-center">
                                {{-- Icon --}}
                                <div class="bg-blue-100 text-blue-600 rounded-full p-5 shadow-lg mb-4 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                                    <i class="fas fa-hospital-user fa-2x"></i>
                                </div>

                                {{-- Nama Unit --}}
                                <h4 class="text-lg font-semibold text-gray-800 group-hover:text-blue-700 leading-tight transition-colors">{{ $unit->nama_unit }}</h4>

                                {{-- Deskripsi --}}
                                @if($unit->deskripsi)
                                    <p class="text-sm mt-2 text-gray-600 group-hover:text-gray-700 transition-colors">{{ $unit->deskripsi }}</p>
                                @endif

                                {{-- CTA --}}
                                <span class="mt-4 text-sm font-semibold text-blue-600 group-hover:text-blue-800 underline transition-all">
                                    Mulai Survei →
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else 
                <div class="mt-6 text-gray-600 text-center italic">
                    🚫 Belum ada unit layanan yang tersedia.
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-in-out;
        }
    </style>
    @endpush
</x-app-layout>
