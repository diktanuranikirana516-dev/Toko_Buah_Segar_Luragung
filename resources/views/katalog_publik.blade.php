
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk - Toko Buah Luragung</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- NAVBAR -->
    <header class="bg-green-700 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <a href="{{ url('/') }}"
               class="text-xl md:text-2xl font-bold tracking-wide">
                🍎 Toko Buah Luragung
            </a>

            <nav class="hidden md:flex space-x-8 font-medium">
                <a href="{{ url('/') }}"
                   class="hover:text-green-200 transition">
                    Home
                </a>

                <a href="{{ route('katalog.publik') }}"
                   class="text-green-200 font-bold border-b-2 border-white pb-1">
                    Products
                </a>
            </nav>

            <div class="flex items-center space-x-4">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="bg-white text-green-700 px-4 py-2 rounded-md font-semibold hover:bg-green-100 transition shadow">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hover:text-green-200 font-medium transition">
                            Log in
                        </a>
                    @endauth
                @endif

            </div>
        </div>
    </header>


    <!-- HEADER KATALOG -->
    <section class="bg-green-600 text-white py-14 text-center">

        <h1 class="text-3xl md:text-4xl font-bold">
            Katalog Lengkap Buah Segar
        </h1>

        <p class="text-green-100 mt-3 text-base md:text-lg">
            Pilih buah favoritmu dan rasakan kesegarannya!
        </p>

    </section>


    <!-- DAFTAR PRODUK -->
    <main class="max-w-7xl mx-auto px-6 py-12">

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Semua Produk Kami
            </h2>

            <p class="text-gray-500 mt-1">
                Temukan berbagai pilihan buah segar berkualitas.
            </p>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-7">

            @forelse($produk as $item)

                @php
                    $gambar = $item->gambar;

                    if ($gambar && !filter_var($gambar, FILTER_VALIDATE_URL)) {
                        $gambar = preg_replace('#^/?storage/#', '', $gambar);
                        $gambar = ltrim($gambar, '/');

                        $gambarUrl = asset('storage/' . $gambar);
                    } else {
                        $gambarUrl = $gambar;
                    }
                @endphp


                <!-- CARD PRODUK -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition duration-300 flex flex-col">


                    <!-- GAMBAR PRODUK (100% Anti-Broken Image) -->
                    <div class="h-52 bg-green-50 overflow-hidden flex items-center justify-center">
                        <img
                            src="{{ $item->gambar_url }}"
                            alt="{{ $item->nama_produk }}"
                            class="w-full h-full object-cover hover:scale-105 transition duration-300"
                            loading="lazy"
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'><rect width=\'100%\' height=\'100%\' fill=\'%23fef3c7\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'64\'>🍎</text></svg>';">
                    </div>


                    <!-- INFORMASI PRODUK -->
                    <div class="p-5 flex-1">

                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $item->nama_produk }}
                        </h3>

                        <p class="text-green-600 font-extrabold text-lg mb-2">
                            Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}
                        </p>

                        <p class="text-sm text-gray-500">
                            Stok tersedia:

                            <span class="font-semibold text-gray-700">
                                {{ $item->stok ?? 0 }} kg
                            </span>
                        </p>

                    </div>


                    <!-- TOMBOL BELI -->
                    <div class="p-5 pt-0">

                        <button
                            onclick="alert('Silakan login terlebih dahulu untuk melakukan pembelian!')"
                            class="w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 transition shadow">

                            Beli Sekarang

                        </button>

                    </div>

                </div>

            @empty

                <!-- JIKA PRODUK KOSONG -->
                <div class="col-span-full text-center py-16">

                    <div class="text-6xl mb-4">
                        🍎
                    </div>

                    <h3 class="text-xl font-bold text-gray-700">
                        Belum Ada Produk
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Saat ini belum ada produk buah yang tersedia.
                    </p>

                </div>

            @endforelse

        </div>

    </main>


    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white text-center py-7">

        <p class="font-medium">
            © 2026 Toko Buah Luragung. All Rights Reserved.
        </p>

        <p class="text-gray-400 text-sm mt-2">
            Menyediakan berbagai pilihan buah segar berkualitas.
        </p>

    </footer>

</body>
</html>
