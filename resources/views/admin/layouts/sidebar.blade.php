{{-- Sidebar --}}
<aside 
    x-show="sidebarOpen"
    x-transition:enter="transition transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition transform ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    :class="{
        'w-64': !sidebarCollapse, 
        'w-16': sidebarCollapse
    }"
    class="bg-blue-900 text-white min-h-screen shadow-md fixed lg:static inset-y-0 left-0 z-50 transform lg:translate-x-0 transition-all duration-300 ease-in-out"
    x-cloak
    @click.outside.window="if (window.innerWidth < 1024) sidebarOpen = false"
>
    {{-- Logo & Collapse/Close Button --}}
    <div class="flex items-center justify-between px-4 py-6">
        <div class="flex items-center gap-2">
            <img src="{{ asset('storage/rsmg.png') }}" alt="Logo" class="w-10 h-10 rounded-full">
            <span class="text-xl font-semibold" x-show="!sidebarCollapse" x-transition>MyDashboard</span>
        </div>

        <div class="flex gap-2">
            <!-- Collapse (desktop) -->
            <button @click="sidebarCollapse = !sidebarCollapse" class="text-white hidden lg:block">
                <i :class="sidebarCollapse ? 'fas fa-chevron-right' : 'fas fa-chevron-left'"></i>
            </button>

            <!-- Close (mobile) -->
            <button @click="sidebarOpen = false" class="text-white lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex flex-col px-2 space-y-1 text-sm">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'icon' => 'fas fa-home', 'route' => 'admin.dashboard', 'check' => 'admin.dashboard'],
                ['label' => 'Unit', 'icon' => 'fas fa-building', 'route' => 'admin.unit.index', 'check' => 'admin.unit.*'],
                ['label' => 'Topik', 'icon' => 'fas fa-tags', 'route' => 'admin.topic.index', 'check' => 'admin.topic.*'],
                ['label' => 'Pertanyaan', 'icon' => 'fas fa-question-circle', 'route' => 'admin.question.index', 'check' => 'admin.question.*'],
                ['label' => 'Identitas', 'icon' => 'fas fa-id-card', 'route' => 'admin.identitas.index', 'check' => 'admin.identitas.*'],
                ['label' => 'Hasil', 'icon' => 'fas fa-comments', 'route' => 'admin.feedback.index', 'check' => 'admin.feedback.*'],
            ];
        @endphp

        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
               @click="if (window.innerWidth < 1024) sidebarOpen = false"
               class="flex items-center gap-3 px-4 py-2 rounded-md transition-all duration-200
                      hover:bg-blue-600 hover:text-white
                      {{ request()->routeIs($item['check']) ? 'bg-blue-700 text-white font-semibold' : 'text-blue-200' }}">
                <i class="{{ $item['icon'] }}"></i>
                <span x-show="!sidebarCollapse" x-transition>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Divider --}}
    <div class="border-t border-blue-500 my-4 mx-4" x-show="!sidebarCollapse"></div>

    {{-- Profile --}}
    @if(Auth::check())
        <div class="px-4 pb-4 mt-auto" x-data="{ dropdown: false }">
            <button @click="dropdown = !dropdown"
                    class="w-full flex justify-between items-center px-3 py-2 bg-blue-600 rounded-md hover:bg-blue-500 transition">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-circle"></i>
                    <span class="text-sm font-medium" x-show="!sidebarCollapse">{{ Auth::user()->name }}</span>
                </div>
                <i class="fas fa-chevron-down text-sm" x-show="!sidebarCollapse"></i>
            </button>

            <div x-show="dropdown" x-transition.duration.200ms
                 class="mt-2 bg-white text-gray-800 rounded-md shadow-lg w-full z-50">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="text-xs text-blue-200 text-center mt-6 px-2" x-show="!sidebarCollapse" x-transition>
        &copy; {{ date('Y') }} RSMG
    </div>
</aside>
