<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Kata Sandi - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen justify-center items-center px-4 sm:px-6 lg:px-8 py-10">

    <div class="max-w-md w-full bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200 my-auto">
        
        <div class="text-center mb-6">
            <span class="text-3xl sm:text-4xl">🔐</span>
            <h2 class="text-xl sm:text-2xl font-black text-stone-900 mt-2">Buat Kata Sandi Baru</h2>
            <p class="text-xs text-stone-500 mt-1">Masukkan kata sandi baru yang aman untuk akun Anda</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Kata Sandi Baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Ulangi Kata Sandi Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                    placeholder="Ulangi kata sandi di atas"
                    class="w-full px-4 py-3 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-stone-50/50">
                @error('password_confirmation')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-xl transition shadow-md text-sm uppercase tracking-wider">
                Simpan Kata Sandi Baru
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-stone-500">
            Ingat kata sandi Anda? 
            <a href="{{ route('login') }}" class="text-amber-600 font-bold hover:underline">Masuk sekarang</a>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-6 sm:mt-8 mb-4 text-center text-[11px] sm:text-xs text-stone-400">
        &copy; 2026 Toko Buah Segar Luragung. All rights reserved.
    </footer>

</body>
</html>
