<section>
    <header>
        <h2 class="text-base font-bold text-stone-900">
            Informasi Profil Pengguna
        </h2>
        <p class="mt-1 text-xs text-stone-500">
            Perbarui nama lengkap dan alamat email yang terhubung dengan akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Nama Lengkap</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-xs" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name" />
            @error('name')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Alamat Email</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-xs" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username" />
            @error('email')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 bg-amber-50 p-3 rounded-xl border border-amber-200">
                    <p class="text-xs text-amber-800">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline font-bold text-amber-900 hover:text-amber-700 ml-1">
                            Kirim ulang email verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-xs text-xs uppercase tracking-wider">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-600"
                >✅ Profil berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
