@extends('admin.layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1: Daftar ID Unit -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar ID Unit Layanan</h2>

            @if(isset($units) && $units > 0)
                <p class="mt-4 font-semibold text-blue-700">
                    Total Unit: {{ $units }}
                </p>
            @else
                <p class="text-gray-500">Belum ada unit layanan yang tersedia.</p>
            @endif
        </div>


                <!-- Card 2: Total Dokter -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Dokter</h2>

            @if(isset($dokters) && $dokters > 0)
                <p class="mt-4 font-semibold text-blue-700">
                    Total Dokter: {{ $dokters }}
                </p>
            @else
                <p class="text-gray-500">Belum ada Dokter yang tersedia.</p>
            @endif
        </div>

                <!-- Card 3: Total Feedback -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Total Feddback Pasien</h2>

            @if(isset($feedback) && $feedback > 0)
                <p class="mt-4 font-semibold text-blue-700">
                    Total Feedback: {{ $feedback }}
                </p>
            @else
                <p class="text-gray-500">Belum ada Feedback yang tersedia.</p>
            @endif
        </div>
    </div>
</div>
@endsection
