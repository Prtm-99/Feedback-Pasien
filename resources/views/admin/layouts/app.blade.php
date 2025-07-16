<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Alpine JS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        sidebarCollapse: false,
        init() {
            window.addEventListener('resize', () => {
                this.sidebarOpen = window.innerWidth >= 1024;
            });
        }
    }"
    x-init="init()"
    :class="{ 'overflow-hidden': sidebarOpen && window.innerWidth < 1024 }"
    class="font-sans antialiased bg-gray-100"
>

    <!-- Header (mobile only) -->
    <header class="bg-white shadow px-4 py-3 flex items-center justify-between lg:hidden">
        <button @click="sidebarOpen = true" class="text-blue-700 text-xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-lg font-semibold text-gray-700">Dashboard Admin</h1>
    </header>

    <!-- Layout Container -->
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            @isset($header)
                <header class="bg-white shadow hidden lg:block">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
