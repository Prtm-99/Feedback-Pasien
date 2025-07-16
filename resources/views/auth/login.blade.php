<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-md mx-auto bg-gradient-to-br from-indigo-50 via-white to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-8 rounded-xl shadow-xl">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">Selamat Datang!</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Silakan masuk ke akun Anda untuk melanjutkan.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Alamat Email')" />
                <div class="relative mt-1">
                    <x-text-input id="email"
                        class="pl-4 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-700 dark:text-white"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus
                        autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Kata Sandi')" />
                <div class="relative mt-1">
                    <x-text-input id="password"
                        class="pl-4 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-700 dark:text-white"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:underline dark:text-indigo-400" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-lg rounded-lg shadow transition duration-200">
                    🔓 Masuk
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
