<section class="space-y-6">
    <header>
        <h2 class="text-base font-bold text-red-600">
            Zona Bahaya: Hapus Akun
        </h2>
        <p class="mt-1 text-xs text-stone-500">
            Setelah akun Anda dihapus, semua data dan riwayat transaksi akan dihapus secara permanen.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider"
    >Hapus Akun Saya</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-4">
            @csrf
            @method('delete')

            <h3 class="text-lg font-bold text-stone-900">
                Apakah Anda yakin ingin menghapus akun?
            </h3>

            <p class="text-xs text-stone-500">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan permanen.
            </p>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-2xl border-stone-300 focus:border-red-500 focus:ring-red-500 text-sm py-3 px-4 shadow-xs"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                />
                @error('password', 'userDeletion')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-stone-100">
                <button type="button" x-on:click="$dispatch('close')" class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider">
                    Batal
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
