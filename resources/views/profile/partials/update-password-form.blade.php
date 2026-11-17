<section>
    <header>
        <h2 class="text-base font-bold text-stone-900">
            Perbarui Kata Sandi (Password)
        </h2>
        <p class="mt-1 text-xs text-stone-500">
            Pastikan akun Anda menggunakan kata sandi yang kuat (min. 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol).
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Kata Sandi Saat Ini</label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-xs" 
                autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Kata Sandi Baru</label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-xs" 
                autocomplete="new-password" />
            @error('password', 'updatePassword')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-xs" 
                autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-xs text-xs uppercase tracking-wider">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-600"
                >✅ Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
