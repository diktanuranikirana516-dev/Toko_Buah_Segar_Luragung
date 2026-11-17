<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Daftar Produk Buah
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Kelola stok, harga, dan foto produk Toko Buah Luragung</p>
            </div>
            <div>
                <a href="{{ route('produk.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider">
                    + Tambah Produk Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm font-medium shadow-sm">
                    ✨ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50/70 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                                <th class="py-3.5 px-6 font-bold">Foto</th>
                                <th class="py-3.5 px-6 font-bold">Nama Buah</th>
                                <th class="py-3.5 px-6 font-bold">Harga / Kg</th>
                                <th class="py-3.5 px-6 font-bold">Stok</th>
                                <th class="py-3.5 px-6 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse ($produk as $item)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <td class="py-4 px-6">
                                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk }}" class="w-10 h-10 rounded-xl object-cover border border-stone-200" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-stone-900">
                                        {{ $item->nama_produk }}
                                    </td>
                                    <td class="py-4 px-6 text-stone-700 font-medium">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $item->stok <= 5 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-stone-100 text-stone-700' }}">
                                            {{ $item->stok }} Kg {{ $item->stok <= 5 ? '(Menipis)' : '' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('produk.edit', $item->id) }}" class="text-amber-600 hover:text-amber-700 font-bold text-xs">
                                                Edit
                                            </a>
                                            <!-- Tombol Hapus dengan Konfirmasi Pop-up -->
                                            <form action="{{ route('produk.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $item->nama_produk }} ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-bold text-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-stone-400 text-sm">
                                        Belum ada produk buah yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $produk->links() }}
            </div>

        </div>
    </div>
</x-app-layout>