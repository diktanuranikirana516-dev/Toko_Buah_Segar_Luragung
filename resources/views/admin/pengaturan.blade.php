<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-stone-900 leading-tight">
            Pengaturan Toko & Kontak WhatsApp
        </h2>
        <p class="text-xs text-stone-500 mt-0.5">Kelola informasi kontak dan profil Toko Buah Luragung</p>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm font-medium shadow-sm">
                    ✨ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm p-8">
                <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Nama Toko</label>
                        <input type="text" name="nama_toko" value="{{ old('nama_toko', $pengaturan->nama_toko) }}" required
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Nomor WhatsApp Admin (Untuk Tombol Pesan Publik)</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $pengaturan->whatsapp) }}" required
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                            placeholder="Contoh: 6285861403683 (Gunakan awalan 62 tanpa +)">
                        <p class="text-xs text-stone-400 mt-1.5">Nomor ini akan otomatis dihubungkan saat pelanggan mengklik tombol pemesanan buah.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Alamat Toko</label>
                        <textarea name="alamat" rows="3"
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">{{ old('alamat', $pengaturan->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Jam Operasional</label>
                        <input type="text" name="jam_operasional" value="{{ old('jam_operasional', $pengaturan->jam_operasional) }}"
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                            placeholder="Contoh: Senin - Minggu (08:00 - 21:00)">
                    </div>

                    <div class="flex justify-end pt-4 border-t border-stone-100">
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-sm text-xs uppercase tracking-wider">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>