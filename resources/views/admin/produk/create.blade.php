<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-stone-900 leading-tight">
            Tambah Produk Buah Baru
        </h2>
        <p class="text-xs text-stone-500 mt-0.5">Masukkan detail informasi buah segar untuk ditambahkan ke katalog</p>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm shadow-sm">
                    <span class="font-bold">⚠️ Perhatian:</span> Mohon periksa kembali formulir di bawah.
                    <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm p-8">
                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Nama Produk Buah</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                            placeholder="Contoh: Apel Malang Segar">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Harga (Rp per Kg/Pack)</label>
                            <input type="number" name="harga" value="{{ old('harga') }}" required min="0"
                                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                                placeholder="Contoh: 25000">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Stok Tersedia</label>
                            <input type="number" name="stok" value="{{ old('stok') }}" required min="0"
                                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                                placeholder="Contoh: 15">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm"
                            placeholder="Tuliskan rasa, tingkat kematangan, atau keunggulan buah...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Foto Buah</label>
                        <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                        <p class="text-xs text-stone-400 mt-1.5">Format yang didukung: JPG, JPEG, PNG, WEBP (Maks. 2MB).</p>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-stone-100">
                        <a href="{{ route('produk.index') }}" class="text-stone-500 hover:text-stone-700 font-bold text-xs uppercase tracking-wider">
                            Batal
                        </a>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-sm text-xs uppercase tracking-wider">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>