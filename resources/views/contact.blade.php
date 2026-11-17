<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungi Kami - Toko Buah Segar Luragung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Responsive Toggle Menu & Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#fcfbf9] text-stone-800 font-sans flex flex-col min-h-screen">

    <!-- NAVBAR UTAMA SERAGAM -->
    @include('layouts.frontend-navbar')

    <!-- Header Banner Contact Us -->
    <section class="bg-stone-100 border-b border-stone-200 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs uppercase tracking-widest text-amber-600 font-bold block mb-2">Layanan Pelanggan</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 mb-3 tracking-tight">Hubungi Kami</h2>
            <p class="text-stone-600 text-sm sm:text-base max-w-xl mx-auto">Ada pertanyaan seputar ketersediaan buah atau ingin memesan? Silakan hubungi admin kami melalui WhatsApp di bawah ini.</p>
        </div>
    </section>

    <!-- Main Content Contact Us -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-16 flex-grow w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Kotak Info Kontak -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-stone-200 flex flex-col justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-stone-900 mb-4">Informasi Kontak</h3>
                    <p class="text-stone-600 text-sm mb-6 leading-relaxed">
                        Kami siap melayani pemesanan grosir maupun eceran buah segar wilayah Luragung dan sekitarnya.
                    </p>
                    <div class="space-y-4 text-sm text-stone-700">
                        <div class="flex items-start space-x-3">
                            <span class="text-xl">📍</span>
                            <span>Samping Surya Toserba, Luragunglandeuh, Kec. Luragung, Kabupaten Kuningan, Jawa Barat 45561</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl">📞</span>
                            <span>+62 858-6140-3683 (Admin Toko)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl">✉️</span>
                            <span>info@tokobuahluragung.my.id</span>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-stone-100 text-xs text-stone-400">
                    Jam Layanan: Senin - Minggu (08.00 - 20.00 WIB)
                </div>
            </div>

            <!-- Kotak Tombol Direct ke WhatsApp (Dengan Pengecekan Login) -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-stone-200 flex flex-col justify-between text-center">
                <div>
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4 shadow-inner">
                        💬
                    </div>
                    <h3 class="text-2xl font-bold text-stone-900 mb-2">Pesan & Chat WhatsApp</h3>
                    <p class="text-stone-600 text-sm mb-6">
                        @auth
                            Halo <strong>{{ Auth::user()->name }}</strong>, klik tombol di bawah ini untuk langsung terhubung dan melakukan pemesanan ke WhatsApp admin kami.
                        @else
                            Untuk melakukan pemesanan dan terhubung ke WhatsApp admin kami, silakan <strong>login atau registrasi akun</strong> terlebih dahulu.
                        @endauth
                    </p>
                </div>
                
                <!-- Pengecekan Status Autentikasi User -->
                @auth
                    <!-- Jika Sudah Login: Langsung Mengarah ke WhatsApp dengan Nama User -->
                    <a href="https://wa.me/6285861403683?text=Halo%20Admin%20Toko%20Buah%20Luragung,%20saya%20{{ urlencode(Auth::user()->name) }}%20ingin%20memesan%20buah%20segar." 
                       target="_blank" 
                       class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold py-4 px-6 rounded-2xl transition shadow-md flex items-center justify-center gap-3 text-base">
                        <span>📱</span> Chat WhatsApp Sekarang
                    </a>
                @else
                    <!-- Jika Belum Login: Arahkan ke Halaman Login / Register -->
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" 
                           class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold py-3.5 px-6 rounded-2xl transition shadow-md flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                            <span>🔒</span> Login untuk Memesan
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="block w-full bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold py-3 px-6 rounded-2xl transition text-xs uppercase tracking-wider">
                                Belum punya akun? Daftar Sekarang
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-stone-200 text-stone-600 text-center py-8">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-xs uppercase tracking-widest text-stone-500">&copy; 2026 Toko Buah Segar Luragung. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>