<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Laporan Penjualan & Valuasi Aset
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Rekapitulasi nilai inventaris dan potensi omset Toko Buah Luragung</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider shadow-sm">
                    🖨️ Cetak Laporan
                </button>
                <a href="{{ route('produk.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider">
                    Kelola Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Ringkasan Kartu Statistik Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Jenis Buah -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Jenis Buah</span>
                        <span class="text-xl">🍎</span>
                    </div>
                    <div class="text-3xl font-black text-stone-900">{{ $totalProduk }} <span class="text-sm font-normal text-stone-500">Item</span></div>
                    <p class="text-xs text-stone-400 mt-2">Variasi buah terdaftar di sistem</p>
                </div>

                <!-- Total Stok Keseluruhan -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Akumulasi Stok</span>
                        <span class="text-xl">📦</span>
                    </div>
                    <div class="text-3xl font-black text-stone-900">{{ $totalStok }} <span class="text-sm font-normal text-stone-500">Kg/Pack</span></div>
                    <p class="text-xs text-stone-400 mt-2">Total kuantitas barang di toko</p>
                </div>

                <!-- Total Potensi Nilai Aset / Pendapatan -->
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Potensi Total Aset</span>
                        <span class="text-xl">💰</span>
                    </div>
                    <div class="text-2xl font-black text-amber-600 mt-1">
                        Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-stone-400 mt-2">Estimasi omset jika seluruh stok terjual</p>
                </div>
            </div>

            <!-- Tabel Rincian Laporan Per Produk -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-100 flex justify-between items-center">
                    <h4 class="font-bold text-stone-900">Rincian Valuasi & Harga Produk</h4>
                    <span class="text-xs text-stone-400 font-medium">Data diperbarui secara real-time</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50/70 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                                <th class="py-3.5 px-6 font-bold">No</th>
                                <th class="py-3.5 px-6 font-bold">Nama Produk</th>
                                <th class="py-3.5 px-6 font-bold">Harga Satuan</th>
                                <th class="py-3.5 px-6 font-bold">Stok Tersedia</th>
                                <th class="py-3.5 px-6 font-bold text-right">Total Nilai (Harga × Stok)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse ($semuaProduk as $index => $item)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <td class="py-4 px-6 text-stone-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6 font-semibold text-stone-900 flex items-center gap-3">
                                        <img src="{{ $item->gambar_url }}"
                                             alt="{{ $item->nama_produk }}"
                                             class="w-10 h-10 rounded-xl object-cover border border-stone-200 shadow-xs"
                                             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                        <span>{{ $item->nama_produk }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-stone-700 font-medium">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $item->stok <= 5 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-stone-100 text-stone-700' }}">
                                            {{ $item->stok }} {{ $item->stok <= 5 ? '(Menipis)' : 'Tersedia' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-stone-900">
                                        Rp {{ number_format($item->harga * $item->stok, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-stone-400 text-sm">
                                        Belum ada data produk buah untuk dilaporkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($semuaProduk->count() > 0)
                            <tfoot>
                                <tr class="bg-stone-50 font-bold border-t border-stone-200 text-stone-900">
                                    <td colspan="4" class="py-4 px-6 text-right uppercase text-xs tracking-wider">Total Keseluruhan Aset:</td>
                                    <td class="py-4 px-6 text-right text-amber-600 text-base">
                                        Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>