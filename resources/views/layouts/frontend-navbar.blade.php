@php
    $storeName = $pengaturan?->nama_toko ?? 'Toko Buah Segar Luragung';
    $storeWa = preg_replace('/[^0-9]/', '', $pengaturan?->whatsapp ?? '6285861403683');
@endphp

<!-- ========================================================================= -->
<!-- NAVBAR UTAMA FRONTEND (STANDAR SERAGAM: HOME, PRODUK, TENTANG, KONTAK)   -->
<!-- ========================================================================= -->
<header x-data="{ mobileMenuOpen: false }" class="bg-white border-b border-stone-200 shadow-xs sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex justify-between items-center">

        <!-- Logo & Nama Toko -->
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-black text-lg sm:text-xl text-stone-900 hover:text-amber-600 transition">
            <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-xs">🍎</span>
            <span>{{ $storeName }}</span>
        </a>

        <!-- Menu Navigasi Esensial (Desktop: 4 Menu Ringkas) -->
        <nav class="hidden md:flex items-center space-x-8 font-semibold text-sm text-stone-600">
            <a href="{{ url('/') }}" 
               class="{{ request()->is('/') ? 'text-amber-600 font-bold border-b-2 border-amber-600 pb-0.5' : 'hover:text-amber-600 transition' }}">
                Home
            </a>
            <a href="{{ route('products.index') }}" 
               class="{{ request()->routeIs('products.*') ? 'text-amber-600 font-bold border-b-2 border-amber-600 pb-0.5' : 'hover:text-amber-600 transition' }}">
                Produk
            </a>
            <a href="{{ url('/about') }}" 
               class="{{ request()->is('about') ? 'text-amber-600 font-bold border-b-2 border-amber-600 pb-0.5' : 'hover:text-amber-600 transition' }}">
                Tentang
            </a>
            <a href="{{ route('contact') }}" 
               class="{{ request()->routeIs('contact') ? 'text-amber-600 font-bold border-b-2 border-amber-600 pb-0.5' : 'hover:text-amber-600 transition' }}">
                Kontak
            </a>
        </nav>

        <!-- Bagian Kanan: Tombol WhatsApp & Akun / Auth -->
        <div class="flex items-center space-x-3">

            <!-- Tombol Aksi Cepat WhatsApp -->
            <a href="https://wa.me/{{ $storeWa }}?text=Halo%20{{ urlencode($storeName) }},%20saya%20ingin%20memesan%20buah%20segar."
               target="_blank"
               rel="noopener noreferrer"
               class="hidden sm:inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 px-3.5 py-1.5 rounded-full text-xs font-bold transition shadow-xs">
                <span>💬</span>
                <span>Pesan via WA</span>
            </a>

            @if (Route::has('login'))
                @auth
                    <!-- Dropdown Akun Pengguna -->
                    <div x-data="{ userDropdownOpen: false }" class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen"
                                @click.away="userDropdownOpen = false"
                                class="flex items-center gap-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 pl-2 pr-3 py-1.5 rounded-full transition focus:outline-none">
                            <span class="w-7 h-7 rounded-full bg-amber-600 text-white font-bold text-xs flex items-center justify-center uppercase shadow-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="text-xs font-bold text-stone-800 max-w-[130px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-stone-500 transition-transform duration-200" :class="{ 'rotate-180': userDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Isi Dropdown Akun -->
                        <div x-show="userDropdownOpen"
                             x-cloak
                             style="display:none;"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-stone-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100 mb-1">
                                <p class="text-[11px] text-stone-400">Masuk sebagai ({{ ucfirst(Auth::user()->role ?? 'User') }})</p>
                                <p class="text-xs font-bold text-stone-800 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                                    📊 Dashboard Admin
                                </a>
                                <a href="{{ route('produk.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                                    🍎 Kelola Produk
                                </a>
                                <a href="{{ route('admin.transaksi') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                                    📦 Data Transaksi
                                </a>
                                <a href="{{ route('admin.audit-logs') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                                    🛡️ Audit Keamanan
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                                    🏠 Belanja Buah (Beranda)
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                                👤 Profil Saya
                            </a>

                            <div class="border-t border-stone-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Tombol Login & Register -->
                    <div class="hidden sm:flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="text-stone-700 hover:text-amber-600 font-bold text-xs uppercase tracking-wider px-3 py-2 rounded-xl transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-xl transition shadow-xs">
                                Daftar
                            </a>
                        @endif
                    </div>
                @endauth
            @endif

            <!-- Hamburger Button Mobile -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-xl text-stone-600 hover:bg-stone-100 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </div>

    <!-- Menu Mobile Dropdown -->
    <div x-show="mobileMenuOpen"
         x-cloak
         @click.away="mobileMenuOpen = false"
         style="display:none;"
         class="md:hidden bg-white border-t border-stone-200 px-6 py-4 space-y-3 font-semibold text-sm text-stone-700 shadow-md">
        <a href="{{ url('/') }}" class="block {{ request()->is('/') ? 'text-amber-600 font-bold' : 'hover:text-amber-600 transition' }}">
            Home
        </a>
        <a href="{{ route('products.index') }}" class="block {{ request()->routeIs('products.*') ? 'text-amber-600 font-bold' : 'hover:text-amber-600 transition' }}">
            Produk
        </a>
        <a href="{{ url('/about') }}" class="block {{ request()->is('about') ? 'text-amber-600 font-bold' : 'hover:text-amber-600 transition' }}">
            Tentang
        </a>
        <a href="{{ route('contact') }}" class="block {{ request()->routeIs('contact') ? 'text-amber-600 font-bold' : 'hover:text-amber-600 transition' }}">
            Kontak
        </a>

        <div class="border-t border-stone-100 my-2 pt-2">
            @auth
                <p class="text-xs text-stone-400">Masuk sebagai <strong>{{ Auth::user()->name }}</strong></p>
                <div class="mt-2 space-y-2">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="block text-xs font-bold text-amber-600">📊 Dashboard Admin</a>
                    @else
                        <a href="{{ route('home') }}" class="block text-xs font-bold text-amber-600">🏠 Belanja Buah (Beranda)</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="block text-xs font-semibold text-stone-700">👤 Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-rose-600 text-left pt-1">🚪 Logout</button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('login') }}" class="text-center py-2 rounded-xl border border-stone-200 text-stone-700 text-xs font-bold">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="text-center py-2 rounded-xl bg-amber-600 text-white text-xs font-bold">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
