<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda - Toko Buah Segar Luragung</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js untuk Responsive Toggle Menu, Keranjang Belanja & Checkout COD -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

@php
    $storeName = $pengaturan?->nama_toko ?? 'Toko Buah Segar Luragung';
    $storeWa = preg_replace('/[^0-9]/', '', $pengaturan?->whatsapp ?? '6285861403683');
    $storeAddress = $pengaturan?->alamat ?? 'Samping Surya Toserba, Luragunglandeuh, Kec. Luragung, Kab. Kuningan, Jawa Barat';
    $storeHours = $pengaturan?->jam_operasional ?? '08:00 - 20:00 WIB';
@endphp

<body x-data="fruitStoreApp()" class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen relative">

    <!-- ========================================================================= -->
    <!-- 1. NAVBAR SERAGAM (HOME, PRODUK, TENTANG, KONTAK)                        -->
    <!-- ========================================================================= -->
    @include('layouts.frontend-navbar')

    <!-- Flash Alert Normal -->
    @if(session('success') && !session('order_success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-xs">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-sm font-bold">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-xs">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-sm font-bold">&times;</button>
            </div>
        </div>
    @endif


    <!-- ========================================================================= -->
    <!-- 2. HERO SECTION SAMBUTAN RINGKAS DENGAN SATU TOMBOL UTAMA                 -->
    <!-- ========================================================================= -->
    <section class="relative bg-cover bg-center py-20 sm:py-28 text-white"
             style="background-image: linear-gradient(rgba(28, 25, 23, 0.72), rgba(28, 25, 23, 0.72)), url('https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=1600&q=80');">
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
            
            <span class="bg-amber-600 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest inline-block mb-4 shadow-xs">
                🌿 100% Buah Segar & Higienis
            </span>

            <h1 class="text-3xl sm:text-5xl font-black mb-5 leading-tight tracking-tight">
                Pesan Buah Segar Pilihan, Bayar di Tempat (COD)
            </h1>

            <p class="text-stone-200 text-sm sm:text-base max-w-2xl mx-auto mb-8 font-light leading-relaxed">
                Pusat belanja aneka buah segar lokal dan impor berkualitas di Luragung, Kuningan. Pesan langsung dari halaman ini tanpa ribet, kurir kami siap antar langsung ke depan pintu rumah Anda!
            </p>

            <!-- Tombol Aksi Cepat Hero -->
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="#katalog-populer"
                   class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-7 py-3.5 rounded-2xl transition shadow-lg text-xs uppercase tracking-wider inline-flex items-center gap-2">
                    <span>🍎 Belanja Buah Sekarang</span>
                    <span>&darr;</span>
                </a>
                <a href="{{ route('products.index') }}"
                   class="bg-white/10 hover:bg-white/20 text-white border border-white/30 font-bold px-6 py-3.5 rounded-2xl transition text-xs uppercase tracking-wider inline-flex items-center gap-1.5">
                    <span>Lihat Semua Katalog</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 3. SECTION GRID PRODUK POPULER (BELI LANGSUNG DI HALAMAN INI / COD)       -->
    <!-- ========================================================================= -->
    <main id="katalog-populer" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-grow w-full">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-2">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-600 font-bold block mb-1">
                    Langsung Antar ke Rumah (COD)
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-stone-900">
                    Produk Buah Segar Ready Stock
                </h2>
            </div>

            <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                <span>Buka Katalog Lengkap</span>
                <span>&rarr;</span>
            </a>
        </div>

        <!-- Grid 4 Kolom Produk Sederhana -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($produk as $item)
                @php
                    $isAvailable = ($item->stok ?? 0) > 0;
                    $fruitName = $item->nama_produk ?? $item->nama ?? 'Buah Segar';
                    $fruitPrice = (int) ($item->harga ?? 0);
                    $fruitPriceFormatted = number_format($fruitPrice, 0, ',', '.');
                    $fruitImage = $item->gambar_url;
                @endphp

                <div class="bg-white rounded-3xl shadow-xs hover:shadow-lg border border-stone-200 transition duration-300 flex flex-col justify-between overflow-hidden group">
                    <div>
                        <!-- Foto Buah dengan Anti-Broken Image Accessor -->
                        <div class="h-48 bg-stone-50 relative overflow-hidden flex items-center justify-center">
                            <img src="{{ $fruitImage }}"
                                 alt="{{ $fruitName }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 loading="lazy"
                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'64\'>🍎</text></svg>';">

                            <!-- Badge Ketersediaan -->
                            <div class="absolute top-3 right-3">
                                @if($isAvailable)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                        Ready ({{ $item->stok }} kg)
                                    </span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Info Buah -->
                        <div class="p-5">
                            <h3 class="text-base font-bold text-stone-900 mb-1 truncate" title="{{ $fruitName }}">
                                {{ $fruitName }}
                            </h3>

                            <p class="text-amber-600 font-extrabold text-lg mb-2">
                                Rp {{ $fruitPriceFormatted }}
                                <span class="text-xs text-stone-400 font-normal">/ kg</span>
                            </p>

                            <p class="text-xs text-stone-500">
                                Sisa Stok: <span class="font-semibold text-stone-700">{{ $item->stok ?? 0 }} kg</span>
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Beli Langsung di Halaman ini & Masuk Keranjang -->
                    <div class="p-5 pt-0 space-y-2">
                        @if($isAvailable)
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Form Tambah ke Keranjang Ber-Token CSRF & Responsive -->
                                <form action="{{ route('cart.add') }}" method="POST" class="w-full"
                                      @submit.prevent="submitAddToCart($event, {
                                          id: {{ $item->id }},
                                          name: '{{ addslashes($fruitName) }}',
                                          price: {{ $fruitPrice }},
                                          price_formatted: '{{ $fruitPriceFormatted }}',
                                          stok: {{ $item->stok ?? 1 }},
                                          image: '{{ $fruitImage }}'
                                      })">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->id }}">
                                    <input type="hidden" name="jumlah" value="1">

                                    <button type="submit" 
                                            class="w-full bg-stone-100 hover:bg-stone-200 text-stone-800 text-center py-2.5 rounded-xl font-bold text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1 shadow-2xs cursor-pointer">
                                        <span>🛒</span>
                                        <span>+ Keranjang</span>
                                    </button>
                                </form>

                                <!-- Tombol Beli Cepat (Langsung Checkout COD In-Page) -->
                                <button type="button" 
                                        @click="buyNow({
                                            id: {{ $item->id }},
                                            name: '{{ addslashes($fruitName) }}',
                                            price: {{ $fruitPrice }},
                                            price_formatted: '{{ $fruitPriceFormatted }}',
                                            stok: {{ $item->stok ?? 1 }},
                                            image: '{{ $fruitImage }}'
                                        })"
                                        class="w-full bg-amber-600 hover:bg-amber-700 text-white text-center py-2.5 rounded-xl font-bold text-[11px] uppercase tracking-wider transition shadow-xs flex items-center justify-center gap-1 cursor-pointer">
                                    <span>🛵</span>
                                    <span>Beli (COD)</span>
                                </button>
                            </div>
                        @else
                            <button disabled class="w-full bg-stone-100 text-stone-400 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-dashed border-stone-300">
                    <p class="text-4xl mb-2">🍎</p>
                    <p class="text-stone-500 text-sm">Belum ada produk buah yang ditampilkan.</p>
                </div>
            @endforelse
        </div>

    </main>


    <!-- ========================================================================= -->
    <!-- 4. FLOATING CART PILL (MUNCUL KETIKA KERANJANG TERISI & USER LOGIN)     -->
    <!-- ========================================================================= -->
    <div x-show="isLoggedIn && cartCount > 0" 
         x-cloak 
         style="display: none;" 
         class="fixed bottom-6 right-6 z-40 animate-bounce-subtle">
        <button @click="cartDrawerOpen = true" 
                class="bg-stone-900 hover:bg-stone-800 text-white px-5 py-3.5 rounded-full shadow-2xl border-2 border-amber-500 flex items-center gap-3 transition">
            <span class="text-xl">🛒</span>
            <div class="text-left">
                <span class="text-[11px] font-bold text-amber-400 block uppercase tracking-wider">Keranjang Belanja</span>
                <span class="text-xs font-black text-white" x-text="cart.length + ' jenis (' + cartCount + ' kg) • Rp ' + formatRupiah(cartTotal)"></span>
            </div>
            <span class="bg-amber-500 text-stone-900 font-extrabold text-[10px] px-2 py-0.5 rounded-full">
                Buka &rarr;
            </span>
        </button>
    </div>


    <!-- ========================================================================= -->
    <!-- 5. MODAL CHECKOUT MULTI-ITEM / SINGLE (BAYAR DI TEMPAT / COD IN-PAGE)    -->
    <!-- ========================================================================= -->
    <div x-show="orderModalOpen" 
         x-cloak 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div @click.away="orderModalOpen = false" 
             class="bg-white rounded-3xl shadow-2xl border border-stone-200 max-w-lg w-full p-6 sm:p-8 space-y-5 animate-scale-in">
            
            <div class="flex justify-between items-start border-b border-stone-100 pb-3">
                <div>
                    <span class="text-amber-600 font-extrabold uppercase tracking-wider text-[10px]" x-text="checkoutMode === 'cart' ? 'Checkout Keranjang Buah' : 'Pesan Buah Cepat'"></span>
                    <h3 class="text-xl font-black text-stone-900" x-text="checkoutMode === 'cart' ? 'Pengantaran Keranjang (' + cart.length + ' Jenis Buah)' : 'Form Pengantaran (Bayar di Tempat)'"></h3>
                </div>
                <button type="button" @click="orderModalOpen = false" class="text-stone-400 hover:text-stone-700 text-xl font-bold">&times;</button>
            </div>

            <!-- Form Submit Checkout -->
            <form method="POST" action="{{ route('checkout') }}" class="space-y-4">
                @csrf
                
                <!-- Payload JSON untuk multi-item keranjang -->
                <input type="hidden" name="cart_items" :value="checkoutItemsPayload">

                <!-- Mode 1: Checkout Beberapa Buah dari Keranjang -->
                <template x-if="checkoutMode === 'cart'">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs font-bold text-stone-500">
                            <span>Daftar Buah yang Dipesan:</span>
                            <button type="button" @click="orderModalOpen = false; cartDrawerOpen = true" class="text-amber-600 hover:underline text-[11px]">Edit di Keranjang</button>
                        </div>

                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="bg-stone-50 p-2.5 rounded-2xl flex items-center justify-between border border-stone-200 gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <img :src="item.image" class="w-10 h-10 rounded-xl object-cover border border-stone-200 shadow-2xs">
                                        <div>
                                            <h4 class="font-bold text-stone-900 text-xs truncate max-w-[130px]" x-text="item.name"></h4>
                                            <p class="text-amber-600 font-extrabold text-[11px]" x-text="'Rp ' + formatRupiah(item.price) + ' / kg'"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1 bg-white px-2 py-0.5 rounded-lg border border-stone-200">
                                            <button type="button" @click="changeCartQty(index, -1)" class="w-5 h-5 rounded bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs flex items-center justify-center">-</button>
                                            <span class="text-xs font-black text-stone-900 px-1" x-text="item.qty + ' kg'"></span>
                                            <button type="button" @click="changeCartQty(index, 1)" class="w-5 h-5 rounded bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs flex items-center justify-center">+</button>
                                        </div>
                                        <div class="text-right min-w-[70px]">
                                            <span class="text-xs font-black text-stone-900 block" x-text="'Rp ' + formatRupiah(item.price * item.qty)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Mode 2: Beli Langsung 1 Buah Cepat -->
                <template x-if="checkoutMode === 'single'">
                    <div>
                        <input type="hidden" name="produk_id" :value="activeFruit.id">
                        <div class="bg-stone-50 p-4 rounded-2xl flex items-center justify-between border border-stone-200">
                            <div class="flex items-center gap-3">
                                <img :src="activeFruit.image" class="w-14 h-14 rounded-xl object-cover border border-stone-200 shadow-2xs">
                                <div>
                                    <h4 class="font-bold text-stone-900 text-xs sm:text-sm truncate max-w-[180px]" x-text="activeFruit.name"></h4>
                                    <p class="text-amber-600 font-extrabold text-xs" x-text="'Rp ' + formatRupiah(activeFruit.price) + ' / kg'"></p>
                                    <p class="text-stone-400 text-[10px]" x-text="'Ready: ' + activeFruit.stok + ' kg'"></p>
                                </div>
                            </div>

                            <!-- Counter Qty -->
                            <div class="flex items-center gap-1.5 bg-white px-2 py-1.5 rounded-xl border border-stone-200 shadow-2xs">
                                <button type="button" @click="if (activeFruit.qty > 1) activeFruit.qty--" class="w-6 h-6 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs flex items-center justify-center">-</button>
                                <input type="number" name="jumlah" :value="activeFruit.qty" min="1" :max="activeFruit.stok" class="w-8 text-center font-bold text-xs bg-transparent border-0 p-0 focus:ring-0" readonly>
                                <span class="text-[10px] text-stone-400 font-semibold">kg</span>
                                <button type="button" @click="if (activeFruit.qty < activeFruit.stok) activeFruit.qty++" class="w-6 h-6 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs flex items-center justify-center">+</button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Total Tagihan Keseluruhan -->
                <div class="bg-amber-50/70 p-3.5 rounded-2xl border border-amber-200 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-stone-600 font-bold block">Total Tagihan (Bayar di Tempat):</span>
                        <span class="text-[10px] text-amber-800 font-semibold" x-text="checkoutMode === 'cart' ? (cart.length + ' jenis buah • ' + cartCount + ' kg') : (activeFruit.qty + ' kg buah')"></span>
                    </div>
                    <span class="text-lg font-black text-amber-700" x-text="'Rp ' + formatRupiah(checkoutGrandTotal)"></span>
                </div>

                <!-- Data Penerima & Pengantaran -->
                <div class="space-y-3 pt-2 border-t border-stone-100">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-600 mb-1">Nama Pemesan</label>
                        <input type="text" 
                               name="nama_pemesan" 
                               value="{{ Auth::user()->name ?? '' }}" 
                               required 
                               placeholder="Nama lengkap Anda..." 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-amber-500 bg-stone-50/50">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-600 mb-1">Nomor WhatsApp / HP Aktif</label>
                        <input type="tel" 
                               name="nomor_telepon" 
                               required 
                               placeholder="Contoh: 0812xxxxxxxx" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-amber-500 bg-stone-50/50">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-600 mb-1">Alamat Lengkap Pengantaran (Luragung & Sekitarnya)</label>
                        <textarea name="alamat_pengiriman" 
                                  rows="2" 
                                  required 
                                  placeholder="Nama jalan/gang, RT/RW, Dusun, Desa, patokan rumah..." 
                                  class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-amber-500 bg-stone-50/50"></textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-600 mb-1">Catatan Tambahan (Opsional)</label>
                        <input type="text" 
                               name="catatan" 
                               placeholder="Contoh: Pilih buah yang matang pas ya pak..." 
                               class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-amber-500 bg-stone-50/50">
                    </div>

                    <!-- Pilihan Metode Pembayaran (Tanpa QR) -->
                    <div class="space-y-2 pt-2 border-t border-stone-100">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-600">Pilih Metode Pembayaran</label>
                        <input type="hidden" name="metode_pembayaran" :value="paymentMethod">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <!-- 1. Cash on Delivery (COD) -->
                            <label @click="paymentMethod = 'COD'" 
                                   :class="paymentMethod === 'COD' ? 'border-amber-500 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-white hover:bg-stone-50'" 
                                   class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition">
                                <input type="radio" value="COD" x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="block text-xs font-bold text-stone-900 flex items-center gap-1">
                                        <span>💵</span>
                                        <span>Bayar di Tempat (COD)</span>
                                    </span>
                                    <span class="block text-[10px] text-stone-500">Bayar tunai saat kurir tiba</span>
                                </div>
                            </label>

                            <!-- 2. Transfer Bank BCA -->
                            <label @click="paymentMethod = 'Transfer Bank BCA'" 
                                   :class="paymentMethod === 'Transfer Bank BCA' ? 'border-amber-500 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-white hover:bg-stone-50'" 
                                   class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition">
                                <input type="radio" value="Transfer Bank BCA" x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="block text-xs font-bold text-stone-900 flex items-center gap-1">
                                        <span>🏦</span>
                                        <span>Transfer Bank BCA</span>
                                    </span>
                                    <span class="block text-[10px] text-stone-500">Rek: 1234-5678-90</span>
                                </div>
                            </label>

                            <!-- 3. Transfer Bank Mandiri -->
                            <label @click="paymentMethod = 'Transfer Bank Mandiri'" 
                                   :class="paymentMethod === 'Transfer Bank Mandiri' ? 'border-amber-500 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-white hover:bg-stone-50'" 
                                   class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition">
                                <input type="radio" value="Transfer Bank Mandiri" x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="block text-xs font-bold text-stone-900 flex items-center gap-1">
                                        <span>🏛️</span>
                                        <span>Transfer Mandiri</span>
                                    </span>
                                    <span class="block text-[10px] text-stone-500">Rek: 9876-5432-10</span>
                                </div>
                            </label>

                            <!-- 4. E-Wallet GoPay -->
                            <label @click="paymentMethod = 'E-Wallet GoPay'" 
                                   :class="paymentMethod === 'E-Wallet GoPay' ? 'border-amber-500 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-white hover:bg-stone-50'" 
                                   class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition">
                                <input type="radio" value="E-Wallet GoPay" x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="block text-xs font-bold text-stone-900 flex items-center gap-1">
                                        <span>📱</span>
                                        <span>E-Wallet GoPay</span>
                                    </span>
                                    <span class="block text-[10px] text-stone-500">0858-6140-3683</span>
                                </div>
                            </label>

                            <!-- 5. E-Wallet OVO -->
                            <label @click="paymentMethod = 'E-Wallet OVO'" 
                                   :class="paymentMethod === 'E-Wallet OVO' ? 'border-amber-500 bg-amber-50/80 shadow-xs ring-1 ring-amber-500' : 'border-stone-200 bg-white hover:bg-stone-50'" 
                                   class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition sm:col-span-2">
                                <input type="radio" value="E-Wallet OVO" x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="block text-xs font-bold text-stone-900 flex items-center gap-1">
                                        <span>🟣</span>
                                        <span>E-Wallet OVO</span>
                                    </span>
                                    <span class="block text-[10px] text-stone-500">0858-6140-3683 a.n Toko Buah Segar</span>
                                </div>
                            </label>
                        </div>

                        <!-- Petunjuk Dinamis Rekening / Pembayaran -->
                        <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-200 text-[11px] text-stone-700">
                            <template x-if="paymentMethod === 'COD'">
                                <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                                    <span>💵</span>
                                    <span>Siapkan uang tunai pas saat kurir tiba di alamat pengantaran Anda.</span>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'Transfer Bank BCA'">
                                <div>
                                    <span class="font-bold text-stone-900">Rekening Tujuan BCA:</span>
                                    <p class="font-mono font-black text-amber-700 text-xs mt-0.5">1234-5678-90 <span class="font-normal text-stone-600 text-[10px]">a.n Toko Buah Segar Luragung</span></p>
                                    <p class="text-[10px] text-stone-500 mt-0.5">Kirim bukti transfer ke WhatsApp admin setelah pesanan dibuat.</p>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'Transfer Bank Mandiri'">
                                <div>
                                    <span class="font-bold text-stone-900">Rekening Tujuan Mandiri:</span>
                                    <p class="font-mono font-black text-amber-700 text-xs mt-0.5">9876-5432-10 <span class="font-normal text-stone-600 text-[10px]">a.n Toko Buah Segar Luragung</span></p>
                                    <p class="text-[10px] text-stone-500 mt-0.5">Kirim bukti transfer ke WhatsApp admin setelah pesanan dibuat.</p>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'E-Wallet GoPay'">
                                <div>
                                    <span class="font-bold text-stone-900">Nomor Akun GoPay:</span>
                                    <p class="font-mono font-black text-amber-700 text-xs mt-0.5">0858-6140-3683 <span class="font-normal text-stone-600 text-[10px]">a.n Toko Buah Segar</span></p>
                                    <p class="text-[10px] text-stone-500 mt-0.5">Kirim bukti pembayaran ke WhatsApp admin.</p>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'E-Wallet OVO'">
                                <div>
                                    <span class="font-bold text-stone-900">Nomor Akun OVO:</span>
                                    <p class="font-mono font-black text-amber-700 text-xs mt-0.5">0858-6140-3683 <span class="font-normal text-stone-600 text-[10px]">a.n Toko Buah Segar</span></p>
                                    <p class="text-[10px] text-stone-500 mt-0.5">Kirim bukti pembayaran ke WhatsApp admin.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-2 flex items-center gap-2.5">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-2xl text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-2">
                        <span>🛵</span>
                        <span>Pesan Sekarang (Bayar Saat Diantar)</span>
                    </button>
                    <button type="button" @click="orderModalOpen = false" class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-3.5 px-5 rounded-2xl text-xs transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 6. DRAWER KERANJANG BELANJA (SLIDE-OVER MULTI-ITEM)                        -->
    <!-- ========================================================================= -->
    <div x-show="cartDrawerOpen" 
         x-cloak 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-hidden bg-stone-900/60 backdrop-blur-xs flex justify-end">
        
        <div @click.away="cartDrawerOpen = false" 
             class="bg-white w-full max-w-md h-full shadow-2xl p-6 sm:p-8 flex flex-col justify-between overflow-y-auto">
            
            <div class="space-y-6">
                <!-- Header Drawer -->
                <div class="flex justify-between items-center border-b border-stone-100 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🛒</span>
                        <div>
                            <h3 class="text-xl font-black text-stone-900">Keranjang Belanja</h3>
                            <p class="text-[11px] text-stone-400 font-medium" x-text="cart.length + ' jenis buah siap dipesan'"></p>
                        </div>
                    </div>
                    <button @click="cartDrawerOpen = false" class="text-stone-400 hover:text-stone-700 text-2xl font-bold">&times;</button>
                </div>

                <!-- List Item di Keranjang -->
                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <img :src="item.image" class="w-12 h-12 rounded-xl object-cover border border-stone-200">
                                <div>
                                    <h4 class="font-bold text-stone-900 text-xs truncate max-w-[130px]" x-text="item.name"></h4>
                                    <p class="text-stone-500 text-[10px]" x-text="'Rp ' + formatRupiah(item.price) + ' / kg'"></p>
                                    <p class="text-amber-600 font-black text-xs mt-0.5" x-text="'Subtotal: Rp ' + formatRupiah(item.price * item.qty)"></p>
                                </div>
                            </div>
                            
                            <!-- Control Qty -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 bg-white px-2 py-1 rounded-lg border border-stone-200">
                                    <button @click="changeCartQty(index, -1)" class="text-stone-600 hover:text-stone-900 font-bold px-1 text-xs">-</button>
                                    <span class="text-xs font-black text-stone-900 px-1" x-text="item.qty + ' kg'"></span>
                                    <button @click="changeCartQty(index, 1)" class="text-stone-600 hover:text-stone-900 font-bold px-1 text-xs">+</button>
                                </div>
                                <button @click="removeFromCart(index)" title="Hapus buah ini" class="text-rose-500 hover:text-rose-700 text-xs p-1 font-bold">&times;</button>
                            </div>
                        </div>
                    </template>

                    <template x-if="cart.length === 0">
                        <div class="text-center py-12 text-stone-400">
                            <span class="text-4xl block mb-2">🛒</span>
                            <p class="text-xs font-medium">Keranjang belanja Anda masih kosong.</p>
                            <p class="text-[11px] text-stone-400 mt-1">Pilih buah segar di atas dan klik <strong>+ Keranjang</strong>.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer Drawer & Checkout COD -->
            <div class="border-t border-stone-100 pt-4 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <span class="font-bold text-stone-600 block">Total Belanja:</span>
                        <span class="text-[10px] text-stone-400" x-text="cart.length + ' jenis buah • ' + cartCount + ' kg'"></span>
                    </div>
                    <span class="font-black text-amber-700 text-lg" x-text="'Rp ' + formatRupiah(cartTotal)"></span>
                </div>

                <template x-if="cart.length > 0">
                    <div class="space-y-2">
                        <button @click="checkoutCart()" 
                                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-2xl text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-2">
                            <span>🛵</span>
                            <span>Checkout Semua Buah (Bayar di Tempat)</span>
                        </button>
                        <button @click="clearCart()" 
                                class="w-full bg-stone-100 hover:bg-rose-50 text-stone-600 hover:text-rose-600 font-semibold py-2 rounded-xl text-[11px] transition">
                            Kosongkan Keranjang
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 7. MODAL STRUK SUKSES PESANAN (POP-UP SETELAH TRANSAKSI BERHASIL)         -->
    <!-- ========================================================================= -->
    @if(session('order_success'))
        @php
            $sukses = session('order_success');
            $waMsg = $sukses['wa_msg'] ?? "Halo Admin {$storeName}, saya telah memesan buah via website dengan kode {$sukses['kode']}";
            $itemsList = $sukses['items'] ?? [];
        @endphp

        <div x-show="orderSuccessModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div class="bg-white rounded-3xl shadow-2xl border border-stone-200 max-w-lg w-full p-6 sm:p-8 space-y-5 animate-scale-in">
                
                <div class="text-center">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-3 shadow-inner">
                        ✓
                    </div>
                    <span class="text-emerald-700 font-extrabold uppercase tracking-wider text-[10px]">Pesanan Berhasil Dibuat</span>
                    <h3 class="text-xl font-black text-stone-900">Buah Segar Segera Diantar!</h3>
                    <p class="text-xs text-stone-500 mt-1">Siapkan uang pas saat kurir tiba di alamat Anda (Bayar di Tempat / COD).</p>
                </div>

                <!-- Rincian Nota Struk Belanja -->
                <div class="bg-stone-50 p-4 rounded-2xl space-y-3 border border-stone-200 text-xs">
                    <div class="flex justify-between items-center pb-2 border-b border-stone-200">
                        <span class="text-stone-400 font-medium">Kode Transaksi:</span>
                        <span class="font-mono font-bold text-stone-900 bg-stone-200/70 px-2 py-0.5 rounded text-[11px]">{{ $sukses['kode'] }}</span>
                    </div>

                    <!-- Daftar Item Buah yang Dipesan -->
                    <div class="space-y-1.5 pb-2 border-b border-stone-200 max-h-40 overflow-y-auto">
                        <p class="font-bold text-stone-700 text-[11px]">Rincian Buah Dipesan ({{ count($itemsList) }} Jenis):</p>
                        @forelse($itemsList as $idx => $it)
                            <div class="flex justify-between items-center text-stone-600 pl-1 py-0.5">
                                <span>{{ $idx + 1 }}. {{ $it['nama'] }} ({{ $it['qty'] }} kg)</span>
                                <span class="font-semibold text-stone-900">Rp {{ number_format($it['subtotal'], 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="flex justify-between items-center text-stone-600 pl-1">
                                <span>{{ $sukses['produk'] ?? 'Buah Segar' }} ({{ $sukses['qty'] ?? 1 }} kg)</span>
                                <span class="font-semibold text-stone-900">Rp {{ $sukses['total'] }}</span>
                            </div>
                        @endforelse
                    </div>

                    <div class="flex justify-between items-center pt-1 border-t border-stone-200">
                        <span class="text-stone-500 font-bold">Total Pembayaran:</span>
                        <span class="font-black text-amber-700 text-base">Rp {{ $sukses['total'] }}</span>
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <span class="text-stone-400 font-medium">Metode Pembayaran:</span>
                        <span class="font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                            {{ $sukses['metode'] ?? 'Cash on Delivery (COD)' }}
                        </span>
                    </div>

                    <div class="pt-2 border-t border-stone-200 text-stone-500 space-y-1">
                        <p class="font-bold text-stone-700 text-[11px]">Penerima & Alamat Pengantaran:</p>
                        <p class="text-[11px] font-semibold text-stone-900">{{ $sukses['nama'] ?? 'Pelanggan' }} &bull; {{ $sukses['telepon'] ?? '' }}</p>
                        <p class="text-[11px] leading-relaxed text-stone-600">{{ $sukses['alamat'] }}</p>
                        @if(!empty($sukses['catatan']) && $sukses['catatan'] !== 'Bayar di Tempat (COD)')
                            <p class="text-[10px] text-stone-400 italic">Catatan: {{ $sukses['catatan'] }}</p>
                        @endif
                    </div>
                </div>

                <!-- Tombol Konfirmasi WhatsApp & Tutup -->
                <div class="space-y-2 pt-1">
                    <a href="https://wa.me/{{ $storeWa }}?text={{ $waMsg }}" 
                       target="_blank" 
                       class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold py-3.5 rounded-2xl text-xs transition shadow-md flex items-center justify-center gap-2 uppercase tracking-wider">
                        <span>💬</span>
                        <span>Konfirmasi via WhatsApp Admin (+62 858-6140-3683)</span>
                    </a>
                    
                    <button type="button" @click="orderSuccessModalOpen = false" class="w-full bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-3 rounded-2xl text-xs transition">
                        Lanjut Belanja di Beranda
                    </button>
                </div>
            </div>
        </div>
    @endif


    <!-- ========================================================================= -->
    <!-- 7. MODAL POP-UP WAJIB LOGIN UNTUK PENGUNJUNG / GUEST                      -->
    <!-- ========================================================================= -->
    <div x-show="loginModalOpen" 
         x-cloak 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div @click.away="loginModalOpen = false" 
             class="bg-white rounded-3xl shadow-2xl border border-stone-200 max-w-sm w-full p-6 text-center space-y-4 animate-scale-in">
            
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto shadow-inner">
                🛒
            </div>

            <div>
                <h3 class="text-lg font-black text-stone-900">Silakan Masuk Terlebih Dahulu</h3>
                <p class="text-xs text-stone-500 mt-1.5 leading-relaxed">
                    Untuk menambahkan buah ke keranjang belanja atau melakukan pemesanan, Anda perlu masuk ke akun terlebih dahulu.
                </p>
            </div>

            <div class="space-y-2 pt-2">
                <a href="{{ route('login') }}" 
                   class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl text-xs uppercase tracking-wider transition shadow-md block text-center">
                    Masuk ke Akun (Login)
                </a>
                
                <a href="{{ route('register') }}" 
                   class="w-full bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold py-2.5 rounded-xl text-xs transition block text-center">
                    Belum Punya Akun? Daftar
                </a>

                <button type="button" @click="loginModalOpen = false" 
                        class="text-[11px] text-stone-400 hover:text-stone-600 font-semibold py-1">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>


    <!-- Toast Notifikasi Tambah Keranjang -->
    <div x-show="showToast" 
         x-cloak 
         style="display: none;" 
         class="fixed bottom-6 left-6 z-50 bg-stone-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2">
        <span x-text="toastMessage"></span>
    </div>


    <!-- ========================================================================= -->
    <!-- 8. FOOTER SEDERHANA DI BAGIAN BAWAH                                       -->
    <!-- ========================================================================= -->
    <footer class="bg-white border-t border-stone-200 text-stone-600 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
            <p class="text-sm font-bold text-stone-800">
                🍎 {{ $storeName }}
            </p>
            <p class="text-xs text-stone-500">
                {{ $storeAddress }} &bull; Jam Operasional: {{ $storeHours }}
            </p>
            <p class="text-xs text-emerald-700 font-semibold">
                WhatsApp Admin: <a href="https://wa.me/{{ $storeWa }}" target="_blank" class="hover:underline font-bold">+62 858-6140-3683</a>
            </p>
            <p class="text-xs text-stone-400 pt-2 border-t border-stone-100">
                &copy; 2026 {{ $storeName }}. All Rights Reserved. (Standar Tugas Kuliah Pemrograman Web)
            </p>
        </div>
    </footer>

    <!-- Script Logika Toko & Keranjang Alpine.js -->
    <script>
        function fruitStoreApp() {
            return {
                isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
                userId: {{ Auth::id() ?? 0 }},
                loginModalOpen: false,
                cart: @json(Auth::check() ? array_values(session()->get('cart_' . Auth::id(), session()->get('cart', []))) : []),
                orderModalOpen: false,
                cartDrawerOpen: false,
                checkoutMode: 'cart',
                paymentMethod: 'COD',
                orderSuccessModalOpen: {{ session('order_success') ? 'true' : 'false' }},
                activeFruit: {
                    id: null,
                    name: '',
                    price: 0,
                    price_formatted: '',
                    stok: 1,
                    image: '',
                    qty: 1
                },
                toastMessage: '',
                showToast: false,

                init() {
                    // Jika belum login, pastikan keranjang bersih
                    if (!this.isLoggedIn) {
                        this.cart = [];
                    } else if (this.orderSuccessModalOpen) {
                        this.cart = [];
                        localStorage.removeItem('fruit_cart_' + this.userId);
                    } else if (this.cart.length === 0) {
                        // Fallback ke localStorage jika sesi baru saja dimuat
                        const saved = localStorage.getItem('fruit_cart_' + this.userId);
                        if (saved) {
                            try {
                                this.cart = JSON.parse(saved);
                            } catch(e) {}
                        }
                    }
                },

                saveCart() {
                    if (this.isLoggedIn) {
                        localStorage.setItem('fruit_cart_' + this.userId, JSON.stringify(this.cart));
                        // Sinkronisasi otomatis ke sesi Laravel terisolasi user
                        fetch('{{ route("cart.sync") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ cart: this.cart })
                        }).catch(() => {});
                    }
                },

                submitAddToCart(event, fruit) {
                    if (!this.isLoggedIn) {
                        this.loginModalOpen = true;
                        return;
                    }

                    const form = event.target;
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => {
                        if (res.status === 401) {
                            this.loginModalOpen = true;
                            return null;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;
                        if (data.success) {
                            this.cart = data.cart;
                            this.saveCart();
                            this.triggerToast(data.message || '✓ Buah masuk ke keranjang!');
                        } else {
                            this.triggerToast('⚠️ ' + (data.message || 'Gagal menambahkan ke keranjang.'));
                        }
                    })
                    .catch(() => {
                        // Fallback jika koneksi lambat
                        this.addToCart(fruit);
                    });
                },

                buyNow(fruit) {
                    if (!this.isLoggedIn) {
                        this.loginModalOpen = true;
                        return;
                    }
                    this.checkoutMode = 'single';
                    this.activeFruit = {
                        id: fruit.id,
                        name: fruit.name,
                        price: fruit.price,
                        price_formatted: fruit.price_formatted,
                        stok: fruit.stok,
                        image: fruit.image,
                        qty: 1
                    };
                    this.orderModalOpen = true;
                },

                checkoutCart() {
                    if (!this.isLoggedIn) {
                        this.loginModalOpen = true;
                        return;
                    }
                    if (this.cart.length === 0) return;
                    this.checkoutMode = 'cart';
                    this.cartDrawerOpen = false;
                    this.orderModalOpen = true;
                },

                addToCart(fruit) {
                    if (!this.isLoggedIn) {
                        this.loginModalOpen = true;
                        return;
                    }
                    const existing = this.cart.find(i => i.id === fruit.id);
                    if (existing) {
                        if (existing.qty < fruit.stok) {
                            existing.qty++;
                        } else {
                            this.triggerToast('⚠️ Stok maksimal tercapai (' + fruit.stok + ' kg)');
                            return;
                        }
                    } else {
                        this.cart.push({
                            id: fruit.id,
                            name: fruit.name,
                            price: fruit.price,
                            price_formatted: fruit.price_formatted,
                            stok: fruit.stok,
                            image: fruit.image,
                            qty: 1
                        });
                    }
                    this.saveCart();
                    this.triggerToast('✓ ' + fruit.name + ' masuk ke keranjang!');
                },

                removeFromCart(index) {
                    const item = this.cart[index];
                    this.cart.splice(index, 1);
                    if (item && item.id) {
                        fetch('/cart/remove/' + item.id, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        }).catch(() => {});
                    }
                    this.saveCart();
                },

                changeCartQty(index, delta) {
                    const item = this.cart[index];
                    if (item) {
                        const newQty = item.qty + delta;
                        if (newQty > 0 && newQty <= item.stok) {
                            item.qty = newQty;
                            item.jumlah = newQty;
                            item.subtotal = newQty * item.price;
                            this.saveCart();
                        } else if (newQty <= 0) {
                            this.removeFromCart(index);
                        } else if (newQty > item.stok) {
                            this.triggerToast('⚠️ Stok maksimal tercapai (' + item.stok + ' kg)');
                        }
                    }
                },

                clearCart() {
                    this.cart = [];
                    fetch('{{ route("cart.clear") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).catch(() => {});
                    this.saveCart();
                    this.triggerToast('Keranjang belanja telah dikosongkan.');
                },

                get cartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get cartCount() {
                    return this.cart.reduce((sum, item) => sum + item.qty, 0);
                },

                get checkoutItemsPayload() {
                    if (this.checkoutMode === 'cart') {
                        return JSON.stringify(this.cart.map(i => ({ produk_id: i.id, jumlah: i.qty })));
                    } else {
                        return JSON.stringify([{ produk_id: this.activeFruit.id, jumlah: this.activeFruit.qty }]);
                    }
                },

                get checkoutGrandTotal() {
                    if (this.checkoutMode === 'cart') {
                        return this.cartTotal;
                    } else {
                        return this.activeFruit.price * this.activeFruit.qty;
                    }
                },

                triggerToast(msg) {
                    this.toastMessage = msg;
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false; }, 2500);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number || 0);
                }
            };
        }
    </script>
</body>
</html>
