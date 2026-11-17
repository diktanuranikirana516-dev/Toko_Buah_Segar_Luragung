
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Buah Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Nama Produk Buah:
                        </label>
                        <input type="text" name="nama_produk"
                            class="border rounded w-full py-2 px-3"
                            value="{{ old('nama_produk') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Harga (Rp):
                        </label>
                        <input type="number" name="harga"
                            class="border rounded w-full py-2 px-3"
                            value="{{ old('harga') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Jumlah Stok (kg):
                        </label>
                        <input type="number" name="stok"
                            class="border rounded w-full py-2 px-3"
                            value="{{ old('stok') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Gambar Produk Buah:
                        </label>
                        <input type="file" name="gambar"
                            accept="image/*"
                            class="border rounded w-full py-2 px-3"
                            required>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Produk
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
