{{-- resources/views/components/app-layout.blade.php --}}
@props(['header'])

<x-layouts.app>
    <x-slot name="header">
        {{ $header }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
