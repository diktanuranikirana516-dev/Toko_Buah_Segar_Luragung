<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>🛒</span>
                    <span>Riwayat Pesanan Belanja Saya</span>
                </h2>
                <p class="text-xs text-stone-500 mt-1">Daftar transaksi pembelian buah segar Anda di Toko Buah Segar Luragung</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.dashboard') }}" class="bg-stone-900 hover:bg-stone-800 text-white font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-2 shadow-xs">
                    <span>&larr;</span>
                    <span>Dashboard Belanja</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#fcfbf9] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-black text-stone-900">Daftar Pesanan Buah</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Seluruh pesanan diproses higienis dan dikirim langsung ke alamat Anda</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-[11px] uppercase font-bold text-stone-400 tracking-wider">
                                <th class="pb-3 px-2">Kode Transaksi</th>
                                <th class="pb-3 px-2">Buah Dipesan</th>
                                <th class="pb-3 px-2 text-center">Jumlah</th>
                                <th class="pb-3 px-2 text-right">Harga Satuan</th>
                                <th class="pb-3 px-2 text-right">Total Biaya</th>
                                <th class="pb-3 px-2">Alamat Pengiriman</th>
                                <th class="pb-3 px-2 text-center">Status</th>
                                <th class="pb-3 px-2 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse($transaksis as $trx)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <!-- Kode TRX -->
                                    <td class="py-4 px-2">
                                        <span class="font-mono font-bold text-xs text-stone-900 bg-stone-100 px-2.5 py-1 rounded-md border border-stone-200 whitespace-nowrap">
                                            {{ $trx->kode_transaksi }}
                                        </span>
                                    </td>

                                    <!-- Produk -->
                                    <td class="py-4 px-2">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $trx->produk?->gambar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>' }}"
                                                 alt="{{ $trx->produk->nama_produk ?? 'Buah Segar' }}"
                                                 class="w-11 h-11 rounded-2xl object-cover border border-stone-200 shadow-xs shrink-0"
                                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                            <div>
                                                <div class="font-bold text-stone-900 text-xs leading-snug">
                                                    {{ $trx->produk->nama_produk ?? 'Buah Segar' }}
                                                </div>
                                                <div class="text-[11px] text-stone-400 mt-0.5">Kualitas Grade A Pilihan</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Jumlah -->
                                    <td class="py-4 px-2 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-stone-100 text-stone-800">
                                            {{ $trx->jumlah }} kg
                                        </span>
                                    </td>

                                    <!-- Harga Satuan -->
                                    <td class="py-4 px-2 text-right text-xs text-stone-600 font-medium">
                                        Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}
                                    </td>

                                    <!-- Total Biaya -->
                                    <td class="py-4 px-2 text-right font-black text-amber-700 text-xs whitespace-nowrap">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>

                                    <!-- Alamat -->
                                    <td class="py-4 px-2 text-xs text-stone-600 max-w-[200px]">
                                        <div class="truncate font-medium" title="{{ $trx->alamat_pengiriman }}">
                                            📍 {{ $trx->alamat_pengiriman }}
                                        </div>
                                        @if($trx->nomor_telepon)
                                            <div class="text-[11px] text-stone-400 mt-0.5">
                                                📞 {{ $trx->nomor_telepon }}
                                            </div>
                                        @endif
                                        @if($trx->catatan)
                                            <div class="text-[10px] text-amber-700 italic truncate mt-0.5" title="{{ $trx->catatan }}">
                                                💬 "{{ $trx->catatan }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-2 text-center">
                                        @if($trx->status === 'selesai')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                ✓ Selesai
                                            </span>
                                        @elseif($trx->status === 'diproses')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                                ⏳ Diproses
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                🕒 Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Waktu -->
                                    <td class="py-4 px-2 text-right text-xs text-stone-400 whitespace-nowrap">
                                        <div>{{ $trx->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-stone-400">{{ $trx->created_at->format('H:i') }} WIB</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-stone-400">
                                        <span class="text-4xl block mb-2">📦</span>
                                        <p class="font-bold text-stone-600">Belum ada riwayat pesanan buah pada akun Anda.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('customer.produk') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl transition shadow-xs">
                                                Mulai Belanja Buah Segar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-between items-center text-xs text-stone-500">
                    <div>Menampilkan data halaman {{ $transaksis->currentPage() }} dari {{ $transaksis->lastPage() }}</div>
                    <div>{{ $transaksis->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
