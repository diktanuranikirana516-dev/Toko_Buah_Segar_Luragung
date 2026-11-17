<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    {{ __('Manajemen Produk Buah') }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Kelola data, harga, stok, dan gambar katalog buah segar</p>
            </div>
            <!-- Tombol Tambah Produk -->
            <a href="{{ route('produk.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider flex items-center gap-1.5">
                <span>+ Tambah Buah Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-sm">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Kotak Pembungkus Tabel -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-100 flex justify-between items-center">
                    <h4 class="font-bold text-stone-900">Daftar Katalog Buah</h4>
                    <span class="text-xs bg-stone-100 text-stone-600 font-bold px-3 py-1 rounded-full">Total: {{ count($produk ?? []) }} Item</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50/70 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                                <th class="py-3.5 px-6 font-bold w-16 text-center">No</th>
                                <th class="py-3.5 px-6 font-bold">Foto</th>
                                <th class="py-3.5 px-6 font-bold">Nama Produk</th>
                                <th class="py-3.5 px-6 font-bold">Harga / Kg</th>
                                <th class="py-3.5 px-6 font-bold">Stok</th>
                                <th class="py-3.5 px-6 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse($produk as $item)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <td class="py-4 px-6 text-center font-medium text-stone-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    
                                    <!-- Kolom Foto Produk (100% Anti-Broken Image) -->
                                    <td class="py-4 px-6">
                                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk ?? $item->nama }}" class="w-12 h-12 rounded-xl object-cover border border-stone-200 shadow-xs" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                    </td>

                                    <!-- Kolom Nama Produk -->
                                    <td class="py-4 px-6 font-bold text-stone-900">
                                        {{ $item->nama_produk ?? $item->nama ?? '-' }}
                                    </td>

                                    <!-- Kolom Harga -->
                                    <td class="py-4 px-6 text-amber-600 font-semibold">
                                        Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}
                                    </td>

                                    <!-- Kolom Stok -->
                                    <td class="py-4 px-6">
                                        @if(($item->stok ?? 0) <= 5)
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                {{ $item->stok ?? 0 }} Kg (Menipis)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-stone-100 text-stone-700">
                                                {{ $item->stok ?? 0 }} Kg
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Kolom Tombol Aksi (Edit & Hapus) -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('produk.edit', $item->id) }}" class="bg-stone-100 hover:bg-stone-900 hover:text-white text-stone-700 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs">
                                                Edit
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-stone-400 text-sm">
                                        <p class="text-3xl mb-2">📦</p>
                                        Belum ada data produk buah yang diinput. Klik <strong>"+ Tambah Buah Baru"</strong> di atas untuk mulai memasukkan data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>