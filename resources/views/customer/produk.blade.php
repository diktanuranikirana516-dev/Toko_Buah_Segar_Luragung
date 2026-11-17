<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-xl text-stone-900 leading-tight">
                    Katalog Buah Segar
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Pilihan buah segar berkualitas prima langsung dari petani</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="bg-white border border-stone-300 hover:bg-stone-50 text-stone-700 font-bold px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-2">
                <span>&larr;</span>
                <span>Kembali ke Dashboard</span>
            </a>
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

            <!-- Form Pencarian Buah -->
            <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm">
                <form method="GET" action="{{ route('customer.produk') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-400">
                            🔍
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama buah segar (misal: Apel, Mangga, Jeruk)..." 
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border-stone-200 focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-2xl transition text-xs uppercase tracking-wider shadow-sm">
                        Cari Buah
                    </button>
                    @if(request('search'))
                        <a href="{{ route('customer.produk') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold px-4 py-3 rounded-2xl transition text-xs uppercase tracking-wider flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Grid Produk Buah -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($produk as $item)
                    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-stone-50 relative overflow-hidden flex items-center justify-center border-b border-stone-100">
                                <img 
                                    src="{{ $item->gambar_url }}" 
                                    alt="{{ $item->nama_produk }}" 
                                    class="w-full h-full object-cover transition duration-300 hover:scale-105"
                                    loading="lazy"
                                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'64\'>🍎</text></svg>';">
                                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-xs text-stone-700 text-xs font-bold px-2.5 py-1 rounded-full border border-stone-200">
                                    Stok: {{ $item->stok }} kg
                                </span>
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-stone-900 text-base mb-1">{{ $item->nama_produk }}</h4>
                                <p class="text-stone-500 text-xs line-clamp-3 mb-4 leading-relaxed">{{ $item->deskripsi ?: 'Buah segar kualitas super pilihan Toko Buah Luragung.' }}</p>
                                <div class="text-amber-600 font-black text-xl">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">/ kg</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            @if($item->stok > 0)
                                <button 
                                    type="button"
                                    @click="openCheckout({ id: '{{ $item->id }}', name: '{{ addslashes($item->nama_produk) }}', price: {{ $item->harga }}, stock: {{ $item->stok }} })"
                                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-2xl transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-xs">
                                    <span>Pesan Buah Ini</span>
                                    <span>&rarr;</span>
                                </button>
                            @else
                                <button disabled class="w-full bg-stone-200 text-stone-400 font-bold py-3 rounded-2xl text-xs uppercase tracking-wider cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-stone-200">
                        <span class="text-5xl mb-3 block">🔍</span>
                        <h4 class="text-stone-800 font-bold text-lg mb-1">Buah tidak ditemukan</h4>
                        <p class="text-stone-500 text-sm">Tidak ada buah yang cocok dengan kata kunci "{{ request('search') }}".</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $produk->links() }}
            </div>

        </div>

        <!-- MODAL CHECKOUT -->
        <div 
            x-show="checkoutOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div 
                @click.away="checkoutOpen = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6">
                
                <div class="flex justify-between items-center border-b border-stone-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Formulir Pemesanan Buah</h3>
                        <p class="text-xs text-stone-500">Isi data pengiriman dengan benar dan akurat.</p>
                    </div>
                    <button @click="checkoutOpen = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('customer.checkout') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="produk_id" :value="selectedProduct.id">

                    <!-- Info Produk -->
                    <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-amber-800 font-bold uppercase tracking-wider">Buah Pilihan</span>
                            <div class="font-bold text-stone-900 text-base" x-text="selectedProduct.name"></div>
                            <div class="text-xs text-stone-500">Harga: <span class="font-bold text-amber-700" x-text="'Rp ' + Number(selectedProduct.price).toLocaleString('id-ID')"></span> / kg</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-stone-400">Stok Tersedia</span>
                            <div class="font-bold text-stone-800" x-text="selectedProduct.stock + ' kg'"></div>
                        </div>
                    </div>

                    <!-- Jumlah Beli -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Jumlah Pembelian (Kg) <span class="text-rose-500">*</span></label>
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
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Nomor WhatsApp / Kontak Penerima <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="nomor_telepon" 
                            required 
                            placeholder="Contoh: 081234567890" 
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Alamat Tujuan Pengiriman <span class="text-rose-500">*</span></label>
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
                            placeholder="Misal: bungkus dalam kardus terpisah" 
                            class="w-full rounded-xl border-stone-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>

                    <!-- Ringkasan Total Biaya -->
                    <div class="pt-2 border-t border-stone-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-stone-600">Total Biaya:</span>
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
                            Konfirmasi Beli
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-app-layout>

