<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Dashboard Pelanggan
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Selamat datang di Toko Buah Segar Luragung</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.produk') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider flex items-center gap-2">
                    🍎 <span>Katalog Buah Lengkap</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfbf9] min-h-screen" x-data="{ 
        checkoutOpen: false, 
        selectedProduct: { id: '', name: '', price: 0, stock: 0 },
        qty: 1,
        openCheckout(prod) {
            this.selectedProduct = prod;
            this.qty = 1;
            this.checkoutOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <p class="font-medium text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- 1. Kartu Sambutan Pelanggan -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl">
                        🛒
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-stone-500 text-sm">Pilih buah segar berkualitas tinggi langsung dari perkebunan terbaik.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-stone-500 bg-stone-50 px-4 py-2 rounded-xl border border-stone-200">
                    <span>Role Akun:</span>
                    <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-md uppercase font-bold text-[10px]">Customer</span>
                </div>
            </div>

            <!-- 2. Statistik Ringkas Belanja -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Pesanan Saya</span>
                        <div class="text-3xl font-black text-stone-900 mt-2">{{ $totalPesanan ?? 0 }} <span class="text-sm font-normal text-stone-500">Transaksi</span></div>
                        <p class="text-xs text-stone-400 mt-1">Seluruh riwayat checkout yang pernah Anda buat</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-2xl border border-amber-100">
                        📦
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Pengeluaran Belanja</span>
                        <div class="text-3xl font-black text-amber-600 mt-2">Rp {{ number_format($totalBelanja ?? 0, 0, ',', '.') }}</div>
                        <p class="text-xs text-stone-400 mt-1">Akumulasi transaksi buah yang telah selesai</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl border border-emerald-100">
                        💳
                    </div>
                </div>
            </div>

            <!-- 3. Buah Segar Terkini (Pilihan Cepat) -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Rekomendasi Buah Segar Hari Ini</h3>
                        <p class="text-xs text-stone-500">Stok siap kirim langsung ke alamat Anda</p>
                    </div>
                    <a href="{{ route('customer.produk') }}" class="text-amber-600 hover:text-amber-700 text-xs font-bold uppercase tracking-wider">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($produk as $item)
                        <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between">
                            <div>
                                <div class="h-44 bg-stone-50 relative overflow-hidden flex items-center justify-center border-b border-stone-100">
                                    <img 
                                        src="{{ $item->gambar_url }}" 
                                        alt="{{ $item->nama_produk }}" 
                                        class="w-full h-full object-cover transition duration-300 hover:scale-105"
                                        loading="lazy"
                                        onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'64\'>🍎</text></svg>';">
                                    <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-xs text-stone-700 text-xs font-bold px-2.5 py-1 rounded-full border border-stone-200">
                                        Stok: {{ $item->stok }}
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h4 class="font-bold text-stone-900 text-base mb-1">{{ $item->nama_produk }}</h4>
                                    <p class="text-stone-500 text-xs line-clamp-2 mb-3">{{ $item->deskripsi ?: 'Buah segar kualitas super pilihan Toko Buah Luragung.' }}</p>
                                    <div class="text-amber-600 font-black text-lg">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">/ kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 pt-0">
                                @if($item->stok > 0)
                                    <button 
                                        type="button"
                                        @click="openCheckout({ id: '{{ $item->id }}', name: '{{ addslashes($item->nama_produk) }}', price: {{ $item->harga }}, stock: {{ $item->stok }} })"
                                        class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 rounded-xl transition text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-xs">
                                        <span>Beli Sekarang</span>
                                        <span>&rarr;</span>
                                    </button>
                                @else
                                    <button disabled class="w-full bg-stone-200 text-stone-400 font-bold py-2.5 rounded-xl text-xs uppercase tracking-wider cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-stone-200">
                            <span class="text-4xl mb-3 block">🍎</span>
                            <p class="text-stone-600 font-bold">Belum ada produk buah yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 4. Riwayat Transaksi Terbaru -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Pesanan Terakhir Saya</h3>
                        <p class="text-xs text-stone-500">Daftar transaksi pembelian buah yang Anda buat</p>
                    </div>
                    <a href="{{ route('customer.transaksi') }}" class="text-amber-600 hover:text-amber-700 font-bold text-xs uppercase tracking-wider">
                        Lihat Riwayat Lengkap &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-xs uppercase font-bold text-stone-400 tracking-wider">
                                <th class="pb-3">Kode Trx</th>
                                <th class="pb-3">Buah</th>
                                <th class="pb-3 text-center">Jumlah</th>
                                <th class="pb-3 text-right">Total Bayar</th>
                                <th class="pb-3 text-center">Status</th>
                                <th class="pb-3 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse($transaksiTerbaru as $trx)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <td class="py-4 font-mono font-bold text-xs text-stone-900">{{ $trx->kode_transaksi }}</td>
                                    <td class="py-4">
                                        <div class="font-bold text-stone-900">{{ $trx->produk->nama_produk ?? 'Buah Segar' }}</div>
                                        <div class="text-xs text-stone-400">Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}/kg</div>
                                    </td>
                                    <td class="py-4 text-center font-bold text-stone-700">{{ $trx->jumlah }} kg</td>
                                    <td class="py-4 text-right font-black text-amber-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                    <td class="py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($trx->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right text-xs text-stone-400">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-stone-400 text-sm">
                                        Belum ada riwayat pesanan buah. Silakan lakukan pemesanan pertama Anda!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL CHECKOUT CEPAT -->
        <div 
            x-show="checkoutOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div 
                @click.away="checkoutOpen = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6">
                
                <div class="flex justify-between items-center border-b border-stone-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Konfirmasi Pemesanan</h3>
                        <p class="text-xs text-stone-500">Lengkapi formulir di bawah ini untuk memesan buah.</p>
                    </div>
                    <button @click="checkoutOpen = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('customer.checkout') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="produk_id" :value="selectedProduct.id">

                    <!-- Info Produk -->
                    <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-amber-800 font-bold uppercase tracking-wider">Produk Terpilih</span>
                            <div class="font-bold text-stone-900 text-base" x-text="selectedProduct.name"></div>
                            <div class="text-xs text-stone-500">Harga: <span class="font-bold text-amber-700" x-text="'Rp ' + Number(selectedProduct.price).toLocaleString('id-ID')"></span> / kg</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-stone-400">Tersedia</span>
                            <div class="font-bold text-stone-800" x-text="selectedProduct.stock + ' kg'"></div>
                        </div>
                    </div>

                    <!-- Jumlah Beli -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Jumlah (Kg) <span class="text-rose-500">*</span></label>
                        <input 
                            type="number" 
                            name="jumlah" 
                            x-model.number="qty" 
                            min="1" 
                            :max="selectedProduct.stock" 
                            required
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm font-bold">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Nomor WhatsApp / Telepon <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="nomor_telepon" 
                            required 
                            placeholder="Contoh: 08123456789" 
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Alamat Lengkap Pengiriman <span class="text-rose-500">*</span></label>
                        <textarea 
                            name="alamat_pengiriman" 
                            rows="2" 
                            required 
                            placeholder="Alamat rumah, jalan, RT/RW, kelurahan..." 
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm"></textarea>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input 
                            type="text" 
                            name="catatan" 
                            placeholder="Misal: pilihkan buah yang matang sempurna" 
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>

                    <!-- Ringkasan Total Biaya -->
                    <div class="pt-2 border-t border-stone-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-stone-600">Total Pembayaran:</span>
                        <div class="text-xl font-black text-amber-600" x-text="'Rp ' + (Number(selectedProduct.price) * (qty || 0)).toLocaleString('id-ID')"></div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="checkoutOpen = false" 
                            class="flex-1 bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-3 rounded-xl transition text-xs uppercase tracking-wider">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl transition text-xs uppercase tracking-wider shadow-sm">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-app-layout>

