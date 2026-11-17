<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Ringkasan inventaris dan laporan katalog Toko Buah Luragung</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" target="_blank" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider">
                    Lihat Toko ↗
                </a>
                <a href="{{ route('produk.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider">
                    + Tambah Buah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. Kartu Sambutan -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-2xl">
                        👋
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-stone-500 text-sm">Selamat bertugas mengelola ketersediaan buah segar hari ini.</p>
                    </div>
                </div>
                <a href="{{ route('produk.index') }}" class="text-amber-600 hover:text-amber-700 font-bold text-sm">
                    Kelola Semua Produk &rarr;
                </a>
            </div>

            <!-- 2. Statistik Ringkas & Laporan (4 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Jenis Produk -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Jenis Buah</span>
                        <span class="text-xl">🍎</span>
                    </div>
                    <div class="text-3xl font-black text-stone-900">{{ $totalProduk ?? 0 }} <span class="text-sm font-normal text-stone-500">Item</span></div>
                    <p class="text-xs text-stone-400 mt-2">Total variasi buah di katalog</p>
                </div>

                <!-- Total Stok Keseluruhan -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Stok</span>
                        <span class="text-xl">📦</span>
                    </div>
                    <div class="text-3xl font-black text-stone-900">{{ $totalStok ?? 0 }} <span class="text-sm font-normal text-stone-500">Kg/Pack</span></div>
                    <p class="text-xs text-stone-400 mt-2">Akumulasi seluruh barang siap jual</p>
                </div>

                <!-- Peringatan Stok Menipis -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Stok Menipis (≤ 5)</span>
                        <span class="text-xl">⚠️</span>
                    </div>
                    <div class="text-3xl font-black {{ ($stokMenipis ?? 0) > 0 ? 'text-red-600' : 'text-stone-900' }}">
                        {{ $stokMenipis ?? 0 }} <span class="text-sm font-normal text-stone-500">Produk</span>
                    </div>
                    <p class="text-xs text-stone-400 mt-2">Segera lakukan restock jika perlu</p>
                </div>

                <!-- Laporan Total Nilai Aset / Potensi Omset -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Nilai Aset</span>
                        <span class="text-xl">💰</span>
                    </div>
                    <div class="text-xl font-black text-amber-600 mt-1">
                        Rp {{ number_format($totalNilaiAset ?? 0, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-stone-400 mt-2">Potensi omset dari total stok</p>
                </div>
            </div>

            <!-- 3. Tabel Pratinjau Produk Terbaru -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-100 flex justify-between items-center">
                    <h4 class="font-bold text-stone-900">Produk Terbaru Ditambahkan</h4>
                    <a href="{{ route('produk.index') }}" class="text-xs font-bold text-amber-600 hover:underline">Lihat Semua</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50/70 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                                <th class="py-3.5 px-6 font-bold">Produk</th>
                                <th class="py-3.5 px-6 font-bold">Harga</th>
                                <th class="py-3.5 px-6 font-bold">Sisa Stok</th>
                                <th class="py-3.5 px-6 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @isset($produkTerbaru)
                                @forelse ($produkTerbaru as $item)
                                    <tr class="hover:bg-stone-50/50 transition">
                                        <td class="py-4 px-6 font-semibold text-stone-900 flex items-center gap-3">
                                            <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk }}" class="w-10 h-10 rounded-xl object-cover border border-stone-200" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                            <span>{{ $item->nama_produk }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-stone-700 font-medium">
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($item->stok <= 5)
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                    {{ $item->stok }} (Menipis)
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-stone-100 text-stone-700">
                                                    {{ $item->stok }} Tersedia
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('produk.edit', $item->id) }}" class="text-amber-600 hover:text-amber-700 font-bold text-xs">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-stone-400 text-sm">
                                            Belum ada data produk buah. Klik "+ Tambah Buah" di pojok kanan atas untuk mulai mengisi katalog.
                                        </td>
                                    </tr>
                                @endforelse
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>