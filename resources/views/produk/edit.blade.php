
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk Buah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <form action="{{ route('produk.update', $produk->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <!-- Nama Produk -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Nama Produk Buah:
                        </label>
                        <input
                            type="text"
                            name="nama_produk"
                            value="{{ old('nama_produk', $produk->nama_produk ?? $produk->nama) }}"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700"
                            required>
                    </div>

                    <!-- Harga -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Harga (Rp):
                        </label>
                        <input
                            type="number"
                            name="harga"
                            value="{{ old('harga', $produk->harga) }}"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700"
                            required>
                    </div>

                    <!-- Stok -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Jumlah Stok (kg):
                        </label>
                        <input
                            type="number"
                            name="stok"
                            value="{{ old('stok', $produk->stok) }}"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700"
                            required>
                    </div>

                    <!-- Gambar Lama -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Gambar Produk Saat Ini:
                        </label>
                        <img
                            src="{{ $produk->gambar_url }}"
                            alt="{{ $produk->nama_produk ?? $produk->nama }}"
                            class="w-40 h-40 object-cover rounded-lg border mb-3"
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                    </div>

                    <!-- Ganti Gambar -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Ganti Gambar Produk (Opsional):
                        </label>
                        <input
                            type="file"
                            name="gambar"
                            accept="image/*"
                            class="block w-full text-sm text-gray-700 border rounded p-2">

                        <p class="text-gray-500 text-xs mt-2">
                            Kosongkan jika tidak ingin mengganti gambar.
                        </p>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                            Perbarui Produk
                        </button>

                        <a href="{{ route('produk.index') }}"
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
