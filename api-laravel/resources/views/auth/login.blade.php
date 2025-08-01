<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | PT Graha Sarana Gresik</title>
    <link rel="icon" href="{{ asset('GSG-Logo-Aja.png') }}" type="image/png">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }

        .eye-icon {
            transition: all 0.2s ease-in-out;
        }

        .eye-icon:hover {
            @apply scale-110;
        }
    </style>
</head>

<body x-data="{ showPassword: false }"
    class="relative min-h-screen bg-cover bg-center bg-no-repeat text-gray-800 dark:text-gray-100"
    style="background-image: url('{{ asset('Auth/BG-GSG.JPG') }}');">

    <!-- Overlay gelap transparan -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    @if ($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="-translate-y-8 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-8 opacity-0"
            class="fixed top-5 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full bg-white/95 dark:bg-gray-800/90 border border-red-300 dark:border-red-700 rounded-2xl shadow-xl p-5 backdrop-blur-md">

            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-700 dark:text-red-300">Oops! Ada yang salah</h3>
                    <ul class="mt-1 text-sm text-gray-700 dark:text-gray-300 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" aria-label="Tutup notifikasi" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <main class="relative z-10 flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">

        <!-- Login Form Container -->
        <section class="w-full max-w-md bg-white/90 dark:bg-gray-900/90 rounded-3xl shadow-2xl p-10 sm:p-12 animate-fade-in-up border border-gray-300 dark:border-gray-700 backdrop-blur-md">

            <!-- Logo -->
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-lg">
                    <img src="{{ asset('GSG-Logo-Aja.png') }}" alt="Logo PT Graha Sarana Gresik" class="w-12 h-12 object-contain">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Masuk ke Sistem</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Akses dashboard internal dengan aman</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        placeholder="Massukan Email"
                        value="{{ old('email') }}"
                        class="w-full px-5 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/90 dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                    />
                </div>

                <!-- Password dengan Ikon di Dalam Input -->
                <div class="mb-8 relative">
                    <label for="password" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Kata Sandi</label>
                    <div class="relative">
                        <!-- Input Password -->
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            placeholder="Massukan Password"
                            class="w-full px-5 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/90 dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        />

                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                            class="absolute inset-y-0 right-0 flex items-center justify-center w-10 h-full text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-transparent focus:outline-none focus:ring-1 focus:ring-indigo-400 rounded-r-xl transition-all duration-150 eye-icon">
                            <template x-if="!showPassword">
                                <!-- Mata tertutup -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </template>
                            <template x-if="showPassword">
                                <!-- Mata terbuka -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-6 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg transition duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-offset-2">
                    Masuk ke Akun
                </button>
            </form>

            <!-- Footer -->
            <footer class="mt-10 pt-6 border-t border-gray-300 dark:border-gray-700 text-center text-xs text-gray-600 dark:text-gray-400 select-none">
                &copy; {{ date('Y') }} <span class="font-semibold text-gray-700 dark:text-white">PT Graha Sarana Gresik</span>. Hak cipta dilindungi.
            </footer>
        </section>
    </main>

</body>
</html>
