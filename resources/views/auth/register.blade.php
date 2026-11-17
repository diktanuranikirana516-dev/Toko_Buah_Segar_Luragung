<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Interaksi Pilihan Role -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen justify-center items-center px-4 sm:px-6 lg:px-8 py-10">

    <!-- Tombol Kembali ke Beranda Publik -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6">
        <a href="{{ url('/') }}" class="text-stone-500 hover:text-amber-600 text-xs sm:text-sm font-semibold flex items-center gap-1 transition">
            ← Kembali ke Beranda
        </a>
    </div>

    <!-- Kotak Utama Form Register (Responsive) -->
    <div class="max-w-md w-full bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200 my-auto">
        
        <!-- Header / Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <span class="text-3xl sm:text-4xl">🍎</span>
            <h2 class="text-xl sm:text-2xl font-black text-stone-900 mt-2">Buat Akun Baru</h2>
            <p class="text-[11px] sm:text-xs text-stone-500 mt-1 uppercase tracking-wider">Toko Buah Segar Luragung</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('name')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('password_confirmation')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pilihan Role Akun (Customer / Admin) -->
            <div x-data="{ selectedRole: '{{ old('role', 'customer') }}' }" class="pt-1">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">
                    Daftar Sebagai (Hak Akses Akun)
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Opsi 1: Pelanggan (Customer) -->
                    <label @click="selectedRole = 'customer'" 
                           :class="selectedRole === 'customer' ? 'border-amber-600 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-stone-50/50 hover:bg-stone-100'"
                           class="flex flex-col items-center justify-center p-3.5 rounded-2xl border-2 cursor-pointer transition text-center select-none">
                        <input type="radio" name="role" value="customer" x-model="selectedRole" class="sr-only">
                        <span class="text-2xl mb-1">🛒</span>
                        <span class="font-extrabold text-xs text-stone-900 block">Pelanggan</span>
                        <span class="text-[10px] text-stone-500 mt-0.5">Beli & Pesan Buah</span>
                    </label>

                    <!-- Opsi 2: Administrator (Admin) -->
                    <label @click="selectedRole = 'admin'" 
                           :class="selectedRole === 'admin' ? 'border-amber-600 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-stone-50/50 hover:bg-stone-100'"
                           class="flex flex-col items-center justify-center p-3.5 rounded-2xl border-2 cursor-pointer transition text-center select-none">
                        <input type="radio" name="role" value="admin" x-model="selectedRole" class="sr-only">
                        <span class="text-2xl mb-1">🛡️</span>
                        <span class="font-extrabold text-xs text-stone-900 block">Administrator</span>
                        <span class="text-[10px] text-stone-500 mt-0.5">Kelola Toko & Pesanan</span>
                    </label>
                </div>
                @error('role')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
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

            <!-- Tombol Register & Link Login -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-xl transition shadow-md text-sm uppercase tracking-wider mb-4">
                    Daftar Akun
                </button>
                
                <div class="text-center">
                    <span class="text-xs text-stone-500">Sudah punya akun? </span>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-amber-600 hover:underline">Log in di sini</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="mt-6 sm:mt-8 mb-4 text-center text-[11px] sm:text-xs text-stone-400">
        &copy; 2026 Toko Buah Segar Luragung. All rights reserved.
    </footer>

</body>
</html>