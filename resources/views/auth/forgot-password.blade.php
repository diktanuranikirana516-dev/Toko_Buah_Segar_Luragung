<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen justify-center items-center px-4 sm:px-6 lg:px-8 py-10">

    <!-- Tombol Kembali ke Halaman Login -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6">
        <a href="{{ route('login') }}" class="text-stone-500 hover:text-amber-600 text-xs sm:text-sm font-semibold flex items-center gap-1 transition">
            ← Kembali ke Login
        </a>
    </div>

    <!-- Kotak Utama Lupa Kata Sandi -->
    <div class="max-w-md w-full bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200 my-auto">
        
        <!-- Header / Logo -->
        <div class="text-center mb-6">
            <span class="text-3xl sm:text-4xl">🔑</span>
            <h2 class="text-xl sm:text-2xl font-black text-stone-900 mt-2">Lupa Kata Sandi?</h2>
            <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                Masukkan alamat email yang terdaftar pada akun Anda. Sistem akan mengirimkan tautan reset kata sandi baru.
            </p>
        </div>

        <!-- Session Status (Notifikasi jika link berhasil dikirim) -->
        @if (session('status'))
            <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold text-center shadow-xs">
                ✅ {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Alamat Email Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    placeholder="nama@email.com"
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Kirim Tautan -->
            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-xl transition shadow-md text-sm uppercase tracking-wider">
                Kirim Tautan Reset Sandi
            </button>
        </form>

        <!-- Bantuan Cepat via WhatsApp Admin -->
        <div class="mt-6 pt-5 border-t border-stone-100 text-center space-y-2">
            <p class="text-xs text-stone-500">Lupa email atau butuh bantuan reset instan?</p>
            <a href="https://wa.me/6285861403683?text=Halo%20Admin%20Toko%20Buah%20Luragung,%20saya%20lupa%20kata%20sandi%20akun%20saya.%20Mohon%20bantuan%20reset%20kata%20sandi." 
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-4 py-2 rounded-xl transition">
                <span>💬</span>
                <span>Hubungi Admin WA (+62 858-6140-3683)</span>
            </a>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-6 sm:mt-8 mb-4 text-center text-[11px] sm:text-xs text-stone-400">
        &copy; 2026 Toko Buah Segar Luragung. All rights reserved.
    </footer>

</body>
</html>
