<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 relative overflow-hidden">
        
        <!-- Background Elemen Dekoratif -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        
        <!-- Card Login -->
        <div class="w-full max-w-4xl bg-white/95 backdrop-blur-lg 
                    p-10 rounded-3xl shadow-2xl flex items-center gap-8 
                    transform transition-all duration-500 hover:scale-[1.02] hover:shadow-blue-200/80">
            
            <!-- Kolom Kiri (Logo & Ilustrasi) -->
            <div class="hidden md:flex flex-col items-center justify-center w-1/3 border-r border-blue-100 pr-6">
                <img src="{{ Vite::asset('public/images/rsmg.png') }}"
                     alt="Logo" 
                     class="w-28 h-28 object-contain mb-4 drop-shadow-md hover:scale-110 transition-transform duration-300">
                <p class="text-blue-600 font-semibold text-center">Sistem Informasi Feedback Pasien</p>
            </div>

            <!-- Kolom Kanan (Form Login) -->
            <div class="w-full md:w-2/3">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-extrabold text-blue-800 tracking-tight">👋 Selamat Datang!</h2>
                    <p class="text-sm text-blue-600 mt-2">Masuk untuk mengakses dashboard Anda</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-blue-900">Alamat Email</label>
                        <div class="relative mt-1">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                            <input id="email"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required autofocus autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 
                                       text-blue-900 bg-blue-50/50 placeholder-blue-300 shadow-sm 
                                       transition duration-200 hover:border-blue-400" 
                                placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-blue-900">Kata Sandi</label>
                        <div class="relative mt-1">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                            <input id="password"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 
                                       text-blue-900 bg-blue-50/50 placeholder-blue-300 shadow-sm 
                                       transition duration-200 hover:border-blue-400" 
                                placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 text-xs" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center text-blue-800">
                            <input type="checkbox" name="remember" 
                                   class="rounded border-blue-300 text-blue-600 focus:ring focus:ring-blue-300">
                            <span class="ml-2">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-blue-600 hover:underline font-medium" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 px-5 py-3 
                                   bg-gradient-to-r from-blue-600 to-blue-800 
                                   hover:from-blue-700 hover:to-blue-900 
                                   text-white text-base font-semibold 
                                   rounded-xl shadow-md hover:shadow-xl 
                                   transform hover:-translate-y-0.5 transition-all duration-300">
                            🔓 Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</x-guest-layout>
