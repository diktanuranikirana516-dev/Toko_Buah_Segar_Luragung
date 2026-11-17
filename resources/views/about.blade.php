<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Responsive Toggle Menu & Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

@php
    $storeName = $pengaturan?->nama_toko ?? 'Toko Buah Segar Luragung';
    $storeWa = preg_replace('/[^0-9]/', '', $pengaturan?->whatsapp ?? '6285861403683');
    $storeAddress = $pengaturan?->alamat ?? 'Samping Surya Toserba, Luragunglandeuh, Kec. Luragung, Kabupaten Kuningan, Jawa Barat 45561';
    $storeHours = $pengaturan?->jam_operasional ?? 'Senin - Minggu: 08.00 - 20.00 WIB';
@endphp

<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen">

    <!-- ========================================================================= -->
    <!-- 1. NAVBAR UTAMA SERAGAM (HOME, PRODUK, TENTANG, KONTAK)                  -->
    <!-- ========================================================================= -->
    @include('layouts.frontend-navbar')


    <!-- ========================================================================= -->
    <!-- 2. HERO BANNER HALAMAN TENTANG KAMI                                       -->
    <!-- ========================================================================= -->
    <section class="bg-stone-100 border-b border-stone-200 py-16 sm:py-20 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs uppercase tracking-widest text-amber-600 font-extrabold block mb-2">Mengenal Lebih Dekat</span>
            <h1 class="text-3xl sm:text-5xl font-black text-stone-900 mb-3 tracking-tight">Tentang {{ $storeName }}</h1>
            <p class="text-stone-600 text-sm sm:text-base max-w-2xl mx-auto">
                Penyedia aneka buah segar pilihan berkualitas tinggi langsung dari perkebunan petani terpercaya untuk kesehatan keluarga Anda.
            </p>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 3. KONTEN CERITA TOKO DENGAN GAMBAR ASLI                                  -->
    <!-- ========================================================================= -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-grow w-full space-y-16">

        <!-- Grid Cerita Kami & Foto Asli Buah / Kebun -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <span class="text-amber-600 font-extrabold uppercase tracking-wider text-xs block mb-2">Cerita & Komitmen Kami</span>
                <h2 class="text-2xl sm:text-3xl font-black text-stone-900 mb-4 leading-tight">Kesegaran Alami yang Terjaga Sejak Dipetik</h2>
                <p class="text-stone-600 leading-relaxed mb-4 text-sm sm:text-base">
                    <strong>{{ $storeName }}</strong> berawal dari komitmen kami untuk menghadirkan aneka buah-buahan bermutu tinggi dan segar setiap hari bagi masyarakat Luragung dan sekitarnya. Kami menyediakan aneka pilihan lengkap, baik buah lokal nusantara maupun buah impor segar berkualitas tinggi.
                </p>
                <p class="text-stone-600 leading-relaxed text-sm sm:text-base">
                    Setiap buah disortir dengan teliti, dijaga kebersihannya, dan disimpan pada suhu optimal tanpa pemanis buatan, sehingga rasa segar dan nutrisinya tetap terjaga alami.
                </p>
            </div>

            <!-- Foto Asli Buah & Kebun (Bukan Emoji Kosong) -->
            <div class="rounded-3xl overflow-hidden shadow-lg border border-stone-200 h-72 sm:h-96 relative group">
                <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=800&q=80"
                     alt="Kebun dan Keranjang Buah Segar Luragung"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute bottom-4 left-4 right-4 bg-stone-900/85 backdrop-blur-md px-4 py-2.5 rounded-2xl text-white flex items-center justify-between shadow-md">
                    <span class="text-xs font-bold text-amber-300">🌿 100% Buah Segar & Matang Alami</span>
                    <span class="text-[11px] text-stone-300">Kec. Luragung, Kuningan</span>
                </div>
            </div>
        </div>

        <!-- 3 Nilai Keunggulan Toko -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-7 rounded-3xl shadow-xs border border-stone-200 text-center hover:shadow-md transition">
                <div class="text-3xl mb-3">🌱</div>
                <h3 class="font-bold text-stone-900 text-base mb-1">100% Segar & Alami</h3>
                <p class="text-xs text-stone-600">Dipetik pada tingkat kematangan optimal tanpa bahan pengawet kimia.</p>
            </div>
            <div class="bg-white p-7 rounded-3xl shadow-xs border border-stone-200 text-center hover:shadow-md transition">
                <div class="text-3xl mb-3">🧺</div>
                <h3 class="font-bold text-stone-900 text-base mb-1">Pilihan Buah Lengkap</h3>
                <p class="text-xs text-stone-600">Menyediakan berbagai ragam buah lokal unggulan dan buah impor favorit keluarga.</p>
            </div>
            <div class="bg-white p-7 rounded-3xl shadow-xs border border-stone-200 text-center hover:shadow-md transition">
                <div class="text-3xl mb-3">🛡️</div>
                <h3 class="font-bold text-stone-900 text-base mb-1">Garansi Kualitas</h3>
                <p class="text-xs text-stone-600">Penyortiran ketat dan garansi ganti baru jika buah cacat atau rusak.</p>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- 4. TABEL KOMODITAS & STANDAR MUTU BUAH DENGAN GAMBAR (YANG DI-REQUEST)    -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-2">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-wider text-amber-600 block mb-1">
                        Spesifikasi Produk
                    </span>
                    <h3 class="text-xl sm:text-2xl font-black text-stone-900">
                        Tabel Komoditas & Standar Mutu Buah
                    </h3>
                </div>
                <p class="text-xs text-stone-400">
                    Standar mutu kebersihan dan kualitas rasa buah kami
                </p>
            </div>

            <!-- Tabel Responsif dengan Gambar Buah di Setiap Baris -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                            <th class="py-3.5 px-6 font-bold">Foto Buah</th>
                            <th class="py-3.5 px-6 font-bold">Komoditas & Nama Buah</th>
                            <th class="py-3.5 px-6 font-bold">Kategori</th>
                            <th class="py-3.5 px-6 font-bold">Standar Mutu & Rasa</th>
                            <th class="py-3.5 px-6 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-sm">
                        @isset($produk)
                            @forelse($produk as $item)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <!-- Kolom Gambar Buah (Anti-Broken Image) -->
                                    <td class="py-4 px-6">
                                        <img src="{{ $item->gambar_url }}"
                                             alt="{{ $item->nama_produk }}"
                                             class="w-12 h-12 rounded-2xl object-cover border border-stone-200 shadow-xs"
                                             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'32\'>🍎</text></svg>';">
                                    </td>
                                    
                                    <!-- Nama Buah -->
                                    <td class="py-4 px-6 font-bold text-stone-900">
                                        {{ $item->nama_produk ?? $item->nama }}
                                        <div class="text-xs font-normal text-stone-500 mt-0.5">
                                             Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }} / kg
                                        </div>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            Buah Segar Pilihan
                                        </span>
                                    </td>

                                    <!-- Standar Mutu -->
                                    <td class="py-4 px-6 text-xs text-stone-600">
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                                            <span>✓</span> Grade A (Higienis & Manis Alami)
                                        </span>
                                    </td>

                                    <!-- Status Ketersediaan -->
                                    <td class="py-4 px-6 text-center">
                                        @if(($item->stok ?? 0) > 0)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                Ready Stock ({{ $item->stok }} kg)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                                Stok Habis
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-stone-400 text-sm">
                                        Data komoditas buah akan segera ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- 5. LOKASI TOKO FISIK & GOOGLE MAPS                                        -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl shadow-xs border border-stone-200 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Sisi Kiri: Alamat & Info Toko -->
                <div class="p-8 sm:p-12 flex flex-col justify-center bg-stone-900 text-white">
                    <span class="bg-amber-600 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider w-fit mb-4">
                        Kunjungi Gerai Kami
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black mb-4">Lokasi Toko Fisik</h3>
                    <p class="text-stone-300 text-sm mb-6 leading-relaxed">
                        Ingin memilih buah-buahan segar secara langsung? Silakan kunjungi outlet toko fisik kami di Luragung, Kuningan.
                    </p>
                    
                    <div class="space-y-4 text-stone-300 border-t border-stone-800 pt-6 text-sm">
                        <div class="flex items-start space-x-3">
                            <span class="text-lg">📍</span>
                            <div>
                                <strong class="block text-white">Alamat Lengkap:</strong>
                                <span class="text-xs text-stone-300">{{ $storeAddress }}</span>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-lg">⏰</span>
                            <div>
                                <strong class="block text-white">Jam Operasional:</strong>
                                <span class="text-xs text-stone-300">{{ $storeHours }}</span>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-lg">📞</span>
                            <div>
                                <strong class="block text-white">Kontak WhatsApp:</strong>
                                <a href="https://wa.me/{{ $storeWa }}" target="_blank" class="text-xs text-amber-400 hover:underline">
                                    +62 858-6140-3683 (Chat Langsung)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Google Maps Embed -->
                <div class="h-80 lg:h-auto min-h-[350px] relative bg-stone-200">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7919.863737806481!2d108.63229041734502!3d-7.017294707085276!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f0d00144fb8bf%3A0x200d52286145b03!2sRN%20Buah%20segar%20luragung!5e0!3m2!1sid!2sid!4v1794760871767!5m2!1sid!2sid" 
                            class="w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer Sederhana -->
    <footer class="bg-white border-t border-stone-200 text-stone-600 text-center py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 space-y-1">
            <p class="text-sm font-bold text-stone-800">🍎 {{ $storeName }}</p>
            <p class="text-xs text-stone-400">&copy; 2026 {{ $storeName }}. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
