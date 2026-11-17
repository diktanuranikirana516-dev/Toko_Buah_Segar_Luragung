<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen justify-center items-center px-4 sm:px-6 lg:px-8">

    <!-- Tombol Kembali ke Beranda Publik -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6">
        <a href="{{ url('/') }}" class="text-stone-500 hover:text-amber-600 text-xs sm:text-sm font-semibold flex items-center gap-1 transition">
            ← Kembali ke Beranda
        </a>
    </div>

    <!-- Kotak Utama Form Login (Responsive di semua ukuran layar) -->
    <div class="max-w-md w-full bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200 my-auto">
        
        <!-- Header / Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <span class="text-3xl sm:text-4xl">🍎</span>
            <h2 class="text-xl sm:text-2xl font-black text-stone-900 mt-2">Masuk ke Akun</h2>
            <p class="text-[11px] sm:text-xs text-stone-500 mt-1 uppercase tracking-wider">Toko Buah Segar Luragung</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-amber-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center text-xs sm:text-sm">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-300 text-amber-600 shadow-sm focus:ring-amber-500">
                    <span class="ms-2 text-xs text-stone-600">Ingat Saya</span>
                </label>
            </div>

            <!-- Google reCAPTCHA Bot Protection (Proporsional & Rapi) -->
            @if(config('services.recaptcha.site_key'))
                <div class="flex flex-col items-center justify-center my-4">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" style="min-width: 304px; min-height: 78px;"></div>
                    @error('g-recaptcha-response')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            @endif

            <!-- Tombol Login -->
            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-xl transition shadow-md text-sm uppercase tracking-wider">
                Masuk
            </button>
        </form>

        <!-- Link Alternatif jika belum punya akun bagi pelanggan -->
        <div class="mt-6 text-center text-xs text-stone-500">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-amber-600 font-bold hover:underline">Daftar sekarang</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-6 sm:mt-8 mb-4 text-center text-[11px] sm:text-xs text-stone-400">
        &copy; 2026 Toko Buah Segar Luragung. All rights reserved.
    </footer>

</body>
</html>