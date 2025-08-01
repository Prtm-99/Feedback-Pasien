<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center 
                bg-gradient-to-br from-blue-200 via-blue-300 to-blue-400 px-4">
        <div class="w-full max-w-sm bg-white/95 backdrop-blur-md 
                    p-6 rounded-xl shadow-xl transition duration-300 
                    hover:shadow-2xl">
            
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-blue-800">Selamat Datang!</h2>
                <p class="text-xs text-blue-700 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-3" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-blue-900">Alamat Email</label>
                    <div class="relative mt-1">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                        <input id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required autofocus autocomplete="username"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-blue-300 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   text-blue-900 bg-white placeholder-blue-300 shadow-sm" 
                            placeholder="nama@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 text-xs" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-blue-900">Kata Sandi</label>
                    <div class="relative mt-1">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                        <input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-blue-300 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   text-blue-900 bg-white placeholder-blue-300 shadow-sm" 
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
                        class="w-full flex justify-center items-center gap-2 px-4 py-2.5 
                               bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold 
                               rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        🔓 Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</x-guest-layout>
