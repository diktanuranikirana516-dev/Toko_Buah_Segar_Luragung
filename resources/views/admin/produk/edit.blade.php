<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-stone-900 leading-tight">
            Edit Produk: {{ $produk->nama_produk }}
        </h2>
        <p class="text-xs text-stone-500 mt-0.5">Perbarui informasi harga, stok, atau foto produk buah</p>
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
                <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Nama Produk Buah</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Harga (Rp per Kg/Pack)</label>
                            <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" required min="0"
                                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Stok Tersedia</label>
                            <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required min="0"
                                class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full rounded-2xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm py-3 px-4 shadow-sm">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Foto Saat Ini</label>
                        <div class="mb-3">
                            <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}" class="w-20 h-20 rounded-2xl object-cover border border-stone-200 shadow-sm" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                        </div>

                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Ganti Foto Buah (Opsional)</label>
                        <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-stone-100">
                        <a href="{{ route('produk.index') }}" class="text-stone-500 hover:text-stone-700 font-bold text-xs uppercase tracking-wider">
                            Batal
                        </a>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-sm text-xs uppercase tracking-wider">
                            Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>