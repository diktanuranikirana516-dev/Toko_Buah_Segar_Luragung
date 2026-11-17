<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2.5">
                    <span>📦</span>
                    <span>Data Transaksi Penjualan Buah</span>
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Pantau seluruh pembelian buah masuk, status pengiriman, dan rekap omzet toko
                </p>
            </div>
            <div class="flex items-center flex-wrap gap-2.5">
                <!-- Tombol Seed Data Simulasi (Jika kosong atau mau re-populate) -->
                <form method="POST" action="{{ route('admin.seed-demo-data') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            title="Muat 12+ data transaksi realistis untuk presentasi tugas kuliah"
                            class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold px-3.5 py-2 rounded-xl transition text-xs flex items-center gap-1.5 shadow-xs">
                        <span>⚡</span>
                        <span>Muat Data Simulasi</span>
                    </button>
                </form>

                <!-- Tombol Ekspor CSV -->
                <a href="{{ route('admin.transaksi.export-csv') }}" 
                   title="Unduh seluruh data transaksi dalam format CSV (Excel ready)"
                   class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-3.5 py-2 rounded-xl transition text-xs flex items-center gap-1.5 shadow-xs">
                    <span>📊</span>
                    <span>Ekspor CSV</span>
                </a>

                <!-- Tombol Cetak / Simpan PDF Laporan -->
                <button onclick="window.print()" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-3.5 py-2 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-1.5 shadow-xs">
                    <span>🖨️</span>
                    <span>Cetak / PDF</span>
                </button>

                <!-- Kembali ke Dashboard -->
                <a href="{{ route('dashboard') }}" class="bg-stone-900 hover:bg-stone-800 text-white font-bold px-4 py-2 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-1.5 shadow-xs">
                    <span>&larr;</span>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#fcfbf9] min-h-screen" x-data="{ invoiceModalOpen: false, activeTrx: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert Success -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-sm font-bold">&times;</button>
                </div>
            @endif

            <!-- ========================================================================= -->
            <!-- 1. STATISTIK RINGKASAN OMZET & PENJUALAN                                  -->
            <!-- ========================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Omzet Pendapatan -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Total Omzet Selesai</span>
                        <div class="text-2xl font-black text-emerald-700 mt-1">
                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-stone-400 font-medium mt-0.5 block">Akumulasi pesanan sukses</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl border border-emerald-100 shadow-xs">
                        💰
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Total Transaksi</span>
                        <div class="text-2xl font-black text-stone-900 mt-1">
                            {{ $totalTransaksi ?? 0 }} <span class="text-xs font-semibold text-stone-400">Pesanan</span>
                        </div>
                        <span class="text-[10px] text-stone-400 font-medium mt-0.5 block">Seluruh order masuk</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl border border-blue-100 shadow-xs">
                        🛒
                    </div>
                </div>

                <!-- Volume Buah Terjual -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Buah Terjual</span>
                        <div class="text-2xl font-black text-amber-600 mt-1">
                            {{ $totalVolume ?? 0 }} <span class="text-xs font-semibold text-stone-400">Kg</span>
                        </div>
                        <span class="text-[10px] text-stone-400 font-medium mt-0.5 block">Volume buah keluar</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl border border-amber-100 shadow-xs">
                        🍎
                    </div>
                </div>

                <!-- Tingkat Keberhasilan Selesai -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Success Rate</span>
                        <div class="text-2xl font-black text-stone-900 mt-1 flex items-center gap-1.5">
                            <span>{{ $persenSelesai ?? 0 }}%</span>
                        </div>
                        <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">✓ Transaksi Terselesaikan</span>
                    </div>
                    <div class="w-12 h-12 bg-stone-100 text-stone-700 rounded-2xl flex items-center justify-center text-2xl border border-stone-200 shadow-xs">
                        📈
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. FILTER & PENCARIAN TRANSAKSI                                           -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-3xl border border-stone-200 p-5 shadow-xs flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                <!-- Tab Status Filter -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-bold">
                    <a href="{{ route('admin.transaksi') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ empty($statusFilter) ? 'bg-stone-900 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('admin.transaksi', ['status' => 'pending']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ in_array($statusFilter, ['pending', 'menunggu']) ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                        🕒 Pending
                    </a>
                    <a href="{{ route('admin.transaksi', ['status' => 'diproses']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($statusFilter === 'diproses') ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                        ⏳ Diproses
                    </a>
                    <a href="{{ route('admin.transaksi', ['status' => 'selesai']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($statusFilter === 'selesai') ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                        ✓ Selesai
                    </a>
                    <a href="{{ route('admin.transaksi', ['status' => 'dibatalkan']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($statusFilter === 'dibatalkan') ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                        ❌ Dibatalkan
                    </a>
                </div>

                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('admin.transaksi') }}" class="flex items-center gap-2">
                    @if($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="relative w-full sm:w-64">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Cari TRX / Pelanggan / Buah..." 
                               class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-stone-50/50">
                        <span class="absolute left-3 top-2.5 text-stone-400 text-xs">🔍</span>
                    </div>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-xs">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.transaksi', array_filter(['status' => $statusFilter])) }}" class="text-stone-400 hover:text-stone-600 text-xs font-semibold py-2 px-1">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- ========================================================================= -->
            <!-- 3. TABEL DATA TRANSAKSI UTAMA                                             -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden p-6 sm:p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-black text-stone-900">Rekapitulasi Penjualan Komoditas</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Menampilkan {{ $transaksis->count() }} dari {{ $transaksis->total() }} total transaksi tercatat</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-[11px] uppercase font-bold text-stone-400 tracking-wider">
                                <th class="pb-3 px-2">Kode Transaksi</th>
                                <th class="pb-3 px-2">Pelanggan</th>
                                <th class="pb-3 px-2">Produk Buah</th>
                                <th class="pb-3 px-2 text-center">Qty</th>
                                <th class="pb-3 px-2 text-right">Harga Satuan</th>
                                <th class="pb-3 px-2 text-right">Total Bayar</th>
                                <th class="pb-3 px-2">Pengiriman & Kontak</th>
                                <th class="pb-3 px-2 text-center">Status</th>
                                <th class="pb-3 px-2 text-right">Waktu</th>
                                <th class="pb-3 px-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse($transaksis as $trx)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <!-- Kode TRX -->
                                    <td class="py-4 px-2">
                                        <span class="font-mono font-bold text-xs text-stone-900 bg-stone-100 px-2 py-1 rounded-md border border-stone-200 whitespace-nowrap">
                                            {{ $trx->kode_transaksi }}
                                        </span>
                                    </td>

                                    <!-- Pelanggan -->
                                    <td class="py-4 px-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-full bg-amber-500 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0 shadow-xs">
                                                {{ substr($trx->user->name ?? 'G', 0, 1) }}
                                            </span>
                                            <div>
                                                <div class="font-bold text-stone-900 text-xs leading-tight">
                                                    {{ $trx->user->name ?? 'Pelanggan Umum' }}
                                                </div>
                                                <div class="text-[11px] text-stone-400 mt-0.5">
                                                    {{ $trx->user->email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Produk Buah -->
                                    <td class="py-4 px-2">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $trx->produk?->gambar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>' }}"
                                                 alt="{{ $trx->produk->nama_produk ?? 'Buah Segar' }}"
                                                 class="w-10 h-10 rounded-xl object-cover border border-stone-200 shadow-xs shrink-0"
                                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                            <div>
                                                <div class="font-bold text-stone-900 text-xs leading-snug">
                                                    {{ $trx->produk->nama_produk ?? 'Komoditas Buah' }}
                                                </div>
                                                <div class="text-[10px] text-stone-400">ID: #{{ $trx->produk_id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Qty -->
                                    <td class="py-4 px-2 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-stone-100 text-stone-800">
                                            {{ $trx->jumlah }} kg
                                        </span>
                                    </td>

                                    <!-- Harga Satuan -->
                                    <td class="py-4 px-2 text-right text-xs text-stone-600 font-medium">
                                        Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}
                                    </td>

                                    <!-- Total Harga & Metode Pembayaran -->
                                    <td class="py-4 px-2 text-right">
                                        <div class="font-black text-amber-700 text-xs whitespace-nowrap">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </div>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            💳 {{ $trx->metode_pembayaran ?? 'COD' }}
                                        </span>
                                    </td>

                                    <!-- Pengiriman & Kontak -->
                                    <td class="py-4 px-2 text-xs text-stone-600 max-w-[200px]">
                                        <div class="truncate font-medium" title="{{ $trx->alamat_pengiriman }}">
                                            📍 {{ $trx->alamat_pengiriman }}
                                        </div>
                                        <div class="text-[11px] text-stone-400 mt-0.5">
                                            📞 {{ $trx->nomor_telepon ?: '-' }}
                                        </div>
                                        @if($trx->catatan)
                                            <div class="text-[10px] text-amber-700 italic truncate mt-0.5" title="{{ $trx->catatan }}">
                                                💬 "{{ $trx->catatan }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status Pesanan -->
                                    <td class="py-4 px-2 text-center">
                                        @if($trx->status === 'selesai')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                ✓ Selesai
                                            </span>
                                        @elseif($trx->status === 'diproses')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                                ⏳ Diproses
                                            </span>
                                        @elseif($trx->status === 'dibatalkan')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                                ❌ Dibatalkan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                🕒 Pending
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Waktu Transaksi -->
                                    <td class="py-4 px-2 text-right text-xs text-stone-400 whitespace-nowrap">
                                        <div>{{ $trx->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-stone-400">{{ $trx->created_at->format('H:i') }} WIB</div>
                                    </td>

                                    <!-- Aksi / Update Status & Invoice Modal -->
                                    <td class="py-4 px-2 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Form Dropdown Ubah Status Interaktif Otomatis Submit -->
                                            <form method="POST" action="{{ route('admin.transaksi.update-status', $trx->id) }}" class="inline-flex items-center">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" 
                                                        onchange="this.form.submit()"
                                                        title="Pilih status baru untuk pesanan ini"
                                                        class="text-[11px] font-bold py-1.5 px-2 rounded-xl border border-stone-300 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer shadow-2xs {{ $trx->status === 'selesai' ? 'text-emerald-700' : ($trx->status === 'diproses' ? 'text-amber-700' : ($trx->status === 'dibatalkan' ? 'text-rose-700' : 'text-blue-700')) }}">
                                                    <option value="pending" {{ in_array($trx->status, ['pending', 'menunggu']) ? 'selected' : '' }}>🕒 Pending</option>
                                                    <option value="diproses" {{ $trx->status === 'diproses' ? 'selected' : '' }}>⏳ Diproses</option>
                                                    <option value="selesai" {{ $trx->status === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                                    <option value="dibatalkan" {{ $trx->status === 'dibatalkan' ? 'selected' : '' }}>❌ Batal</option>
                                                </select>
                                            </form>

                                            <!-- Tombol Struk -->
                                            <button @click="activeTrx = {{ json_encode([
                                                'kode'     => $trx->kode_transaksi,
                                                'nama'     => $trx->user->name ?? 'Pelanggan',
                                                'email'    => $trx->user->email ?? '-',
                                                'produk'   => $trx->produk->nama_produk ?? 'Buah Segar',
                                                'qty'      => $trx->jumlah,
                                                'harga'    => number_format($trx->harga_satuan, 0, ',', '.'),
                                                'total'    => number_format($trx->total_harga, 0, ',', '.'),
                                                'status'   => ucfirst($trx->status),
                                                'alamat'   => $trx->alamat_pengiriman,
                                                'telepon'  => $trx->nomor_telepon,
                                                'catatan'  => $trx->catatan,
                                                'tanggal'  => $trx->created_at->format('d F Y, H:i') . ' WIB',
                                            ]) }}; invoiceModalOpen = true"
                                                    title="Lihat rincian & cetak struk"
                                                    class="bg-stone-100 hover:bg-stone-200 text-stone-700 px-2.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs">
                                                📄 Struk
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-stone-400">
                                        <span class="text-4xl block mb-2">📦</span>
                                        <p class="font-bold text-stone-600">Belum ada transaksi penjualan yang cocok.</p>
                                        <p class="text-xs text-stone-400 mt-1">Gunakan tombol "Muat Data Simulasi" di bagian atas untuk mengisi data contoh.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-between items-center text-xs text-stone-500">
                    <div>Menampilkan data halaman {{ $transaksis->currentPage() }} dari {{ $transaksis->lastPage() }}</div>
                    <div>{{ $transaksis->links() }}</div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 4. MODAL STRUK / INVOICE TRANSAKSI (POP-UP ELEGAN)                        -->
        <!-- ========================================================================= -->
        <div x-show="invoiceModalOpen" 
             x-cloak 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="invoiceModalOpen = false" 
                 class="bg-white rounded-3xl shadow-2xl border border-stone-200 max-w-lg w-full p-6 sm:p-8 space-y-6 animate-scale-in">
                
                <!-- Header Struk -->
                <div class="text-center border-b border-stone-100 pb-4">
                    <span class="text-3xl block mb-1">🍎</span>
                    <h4 class="font-black text-xl text-stone-900">Toko Buah Segar Luragung</h4>
                    <p class="text-xs text-stone-500">Samping Surya Toserba, Luragunglandeuh, Kuningan</p>
                    <p class="text-xs text-emerald-700 font-bold mt-0.5">WhatsApp Admin: +62 858-6140-3683</p>
                </div>

                <!-- Rincian Transaksi -->
                <template x-if="activeTrx">
                    <div class="space-y-4 text-xs">
                        <div class="bg-stone-50 p-4 rounded-2xl space-y-2 border border-stone-100">
                            <div class="flex justify-between">
                                <span class="text-stone-400">Kode Transaksi:</span>
                                <span class="font-mono font-bold text-stone-900" x-text="activeTrx.kode"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-400">Waktu Order:</span>
                                <span class="font-medium text-stone-700" x-text="activeTrx.tanggal"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-400">Status Pesanan:</span>
                                <span class="font-bold text-emerald-700" x-text="'✓ ' + activeTrx.status"></span>
                            </div>
                        </div>

                        <div class="border-b border-stone-100 pb-3 space-y-1">
                            <p class="font-bold text-stone-700 uppercase tracking-wider text-[10px]">Tujuan Pengiriman</p>
                            <p class="font-bold text-stone-900" x-text="activeTrx.nama + ' (' + activeTrx.telepon + ')'"></p>
                            <p class="text-stone-600 leading-relaxed" x-text="activeTrx.alamat"></p>
                        </div>

                        <!-- Item Buah -->
                        <div class="border-b border-stone-100 pb-3">
                            <p class="font-bold text-stone-700 uppercase tracking-wider text-[10px] mb-2">Item Belanja</p>
                            <div class="flex justify-between items-center py-1">
                                <div>
                                    <p class="font-bold text-stone-900" x-text="activeTrx.produk"></p>
                                    <p class="text-stone-400 text-[11px]" x-text="activeTrx.qty + ' kg x Rp ' + activeTrx.harga"></p>
                                </div>
                                <div class="font-extrabold text-stone-900" x-text="'Rp ' + activeTrx.total"></div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center text-sm font-black pt-1">
                            <span class="text-stone-900">Total Pembayaran</span>
                            <span class="text-amber-600 text-lg" x-text="'Rp ' + activeTrx.total"></span>
                        </div>
                    </div>
                </template>

                <!-- Tombol Aksi Modal -->
                <div class="flex items-center gap-3 pt-2">
                    <button @click="window.print()" class="flex-1 bg-stone-900 hover:bg-stone-800 text-white font-bold py-3 rounded-2xl text-xs transition shadow-xs">
                        🖨️ Cetak Struk
                    </button>
                    <button @click="invoiceModalOpen = false" class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-3 px-5 rounded-2xl text-xs transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>