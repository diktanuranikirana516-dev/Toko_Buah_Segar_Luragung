<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2.5">
                    <span>🛡️</span>
                    <span>Audit Keamanan Siber & Log Forensik</span>
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Pemantauan aktivitas otentikasi, mitigasi bot/brute-force OWASP Top 10, dan jejak mutasi data
                </p>
            </div>
            <div class="flex items-center flex-wrap gap-2.5">
                <!-- Tombol Seed Data Simulasi -->
                <form method="POST" action="{{ route('admin.seed-demo-data') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            title="Muat 12+ data log audit forensik untuk simulasi keamanan tugas kuliah"
                            class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold px-3.5 py-2 rounded-xl transition text-xs flex items-center gap-1.5 shadow-xs">
                        <span>⚡</span>
                        <span>Muat Data Simulasi</span>
                    </button>
                </form>

                <!-- Tombol Ekspor CSV -->
                <a href="{{ route('admin.audit-logs.export-csv') }}" 
                   title="Unduh seluruh rekam jejak audit keamanan dalam format CSV"
                   class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-3.5 py-2 rounded-xl transition text-xs flex items-center gap-1.5 shadow-xs">
                    <span>📊</span>
                    <span>Ekspor CSV</span>
                </a>

                <!-- Tombol Cetak / PDF Log -->
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

    <div class="py-8 bg-[#fcfbf9] min-h-screen">
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
            <!-- 1. KARTU STATISTIK KEAMANAN SIBER                                         -->
            <!-- ========================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Log Forensik -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Total Log Audit</span>
                        <div class="text-2xl font-black text-stone-900 mt-1">
                            {{ $totalLogs ?? 0 }} <span class="text-xs font-semibold text-stone-400">Kejadian</span>
                        </div>
                        <span class="text-[10px] text-stone-400 font-medium mt-0.5 block">Jejak aktivitas forensik</span>
                    </div>
                    <div class="w-12 h-12 bg-stone-100 text-stone-700 rounded-2xl flex items-center justify-center text-2xl border border-stone-200 shadow-xs">
                        📋
                    </div>
                </div>

                <!-- Otentikasi Sukses -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Login Terverifikasi</span>
                        <div class="text-2xl font-black text-emerald-700 mt-1">
                            {{ $loginSuccessCount ?? 0 }} <span class="text-xs font-semibold text-emerald-600">Sesi</span>
                        </div>
                        <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">✓ Autentikasi Sah</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl border border-emerald-100 shadow-xs">
                        🔑
                    </div>
                </div>

                <!-- Mitigasi Brute Force -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Mitigasi Brute-Force</span>
                        <div class="text-2xl font-black text-rose-600 mt-1">
                            {{ $failedAttempts ?? 0 }} <span class="text-xs font-semibold text-rose-500">Diredam</span>
                        </div>
                        <span class="text-[10px] text-rose-600 font-bold mt-0.5 block">Rate Limit & Lockout Aktif</span>
                    </div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl border border-rose-100 shadow-xs">
                        🚨
                    </div>
                </div>

                <!-- Standar Kepatuhan OWASP -->
                <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Kepatuhan OWASP</span>
                        <div class="text-xl font-black text-purple-700 mt-1 flex items-center gap-1.5">
                            <span>🛡️</span>
                            <span>Level 2 ASVS</span>
                        </div>
                        <span class="text-[10px] text-purple-600 font-bold mt-0.5 block">Data Sensitif Termasking</span>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl border border-purple-100 shadow-xs">
                        🔒
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. FILTER EVENT & PENCARIAN FORENSIK                                      -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-3xl border border-stone-200 p-5 shadow-xs flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                <!-- Tab Event Filter -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-bold">
                    <a href="{{ route('admin.audit-logs') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ empty($eventFilter) ? 'bg-stone-900 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Semua Event
                    </a>
                    <a href="{{ route('admin.audit-logs', ['event' => 'LOGIN_SUCCESS']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($eventFilter === 'LOGIN_SUCCESS') ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                        🔑 Login Sukses
                    </a>
                    <a href="{{ route('admin.audit-logs', ['event' => 'LOGIN_FAILED']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($eventFilter === 'LOGIN_FAILED') ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                        🚨 Login Gagal
                    </a>
                    <a href="{{ route('admin.audit-logs', ['event' => 'TRANSACTION_CREATED']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($eventFilter === 'TRANSACTION_CREATED') ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                        🛒 Transaksi
                    </a>
                    <a href="{{ route('admin.audit-logs', ['event' => 'REGISTER']) }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ ($eventFilter === 'REGISTER') ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                        📝 Registrasi
                    </a>
                </div>

                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('admin.audit-logs') }}" class="flex items-center gap-2">
                    @if($eventFilter)
                        <input type="hidden" name="event" value="{{ $eventFilter }}">
                    @endif
                    <div class="relative w-full sm:w-64">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Cari deskripsi / IP / user..." 
                               class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-stone-50/50">
                        <span class="absolute left-3 top-2.5 text-stone-400 text-xs">🔍</span>
                    </div>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-xs">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.audit-logs', array_filter(['event' => $eventFilter])) }}" class="text-stone-400 hover:text-stone-600 text-xs font-semibold py-2 px-1">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- ========================================================================= -->
            <!-- 3. TABEL AUDIT TRAIL FORENSIK                                             -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden p-6 sm:p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-black text-stone-900">Jejak Rekaman Forensik Keamanan (Audit Trail)</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Mencatat seluruh mutasi data sensitif, otentikasi sesi, dan percobaan pembobolan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-[11px] uppercase font-bold text-stone-400 tracking-wider">
                                <th class="pb-3 px-2">Waktu (WIB)</th>
                                <th class="pb-3 px-2">Tipe Kejadian</th>
                                <th class="pb-3 px-2">Deskripsi Aktivitas</th>
                                <th class="pb-3 px-2">IP Client</th>
                                <th class="pb-3 px-2">Subjek Pengguna</th>
                                <th class="pb-3 px-2">Rincian Metadata (Masked)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @forelse($logs as $log)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <!-- Timestamp -->
                                    <td class="py-4 px-2 whitespace-nowrap">
                                        <div class="font-mono text-xs font-bold text-stone-800">
                                            {{ $log->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="font-mono text-[11px] text-stone-400">
                                            {{ $log->created_at->format('H:i:s') }} WIB
                                        </div>
                                    </td>

                                    <!-- Event Type Badge -->
                                    <td class="py-4 px-2 whitespace-nowrap">
                                        @if($log->event_type === 'LOGIN_SUCCESS')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span>🔑</span>
                                                <span>LOGIN_SUCCESS</span>
                                            </span>
                                        @elseif($log->event_type === 'LOGIN_FAILED')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                                <span>🚨</span>
                                                <span>LOGIN_FAILED</span>
                                            </span>
                                        @elseif($log->event_type === 'LOGIN_LOCKOUT')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                                <span>🔒</span>
                                                <span>LOGIN_LOCKOUT</span>
                                            </span>
                                        @elseif($log->event_type === 'TRANSACTION_CREATED')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                <span>🛒</span>
                                                <span>ORDER_CREATED</span>
                                            </span>
                                        @elseif($log->event_type === 'REGISTER')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span>📝</span>
                                                <span>USER_REGISTER</span>
                                            </span>
                                        @elseif($log->event_type === 'PRODUCT_UPDATE')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-teal-100 text-teal-800 border border-teal-200">
                                                <span>🍎</span>
                                                <span>STOCK_MUTATION</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-stone-100 text-stone-800 border border-stone-200">
                                                <span>⚡</span>
                                                <span>{{ $log->event_type }}</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="py-4 px-2">
                                        <div class="font-bold text-stone-900 text-xs leading-snug">
                                            {{ $log->description }}
                                        </div>
                                        <div class="text-[11px] text-stone-400 truncate max-w-sm mt-0.5" title="{{ $log->user_agent }}">
                                            {{ Str::limit($log->user_agent, 45) }}
                                        </div>
                                    </td>

                                    <!-- IP Address -->
                                    <td class="py-4 px-2 whitespace-nowrap">
                                        <span class="font-mono text-xs font-semibold text-stone-700 bg-stone-100 px-2.5 py-1 rounded-lg border border-stone-200">
                                            {{ $log->ip_address ?: '127.0.0.1' }}
                                        </span>
                                    </td>

                                    <!-- User Terkait -->
                                    <td class="py-4 px-2 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="flex items-center gap-2">
                                                <span class="w-7 h-7 rounded-full bg-amber-500 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0 shadow-xs">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </span>
                                                <div>
                                                    <div class="font-bold text-stone-900 text-xs">{{ $log->user->name }}</div>
                                                    <div class="text-[10px] text-amber-700 font-semibold uppercase">{{ $log->user->role ?? 'User' }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-stone-400 italic">Anonim / Sistem Tamu</span>
                                        @endif
                                    </td>

                                    <!-- Rincian Data (JSON Formatted Badges) -->
                                    <td class="py-4 px-2">
                                        @if(is_array($log->details) && count($log->details) > 0)
                                            <div class="flex flex-wrap gap-1 max-w-xs">
                                                @foreach($log->details as $k => $v)
                                                    <span class="inline-flex items-center gap-1 bg-stone-100 text-stone-700 text-[10px] px-2 py-0.5 rounded-md border border-stone-200 font-mono">
                                                        <strong class="text-stone-500">{{ $k }}:</strong>
                                                        <span class="text-stone-800 font-bold">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-stone-300 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-stone-400">
                                        <span class="text-4xl block mb-2">🛡️</span>
                                        <p class="font-bold text-stone-600">Belum ada rekaman audit log keamanan.</p>
                                        <p class="text-xs text-stone-400 mt-1">Klik tombol "Muat Data Simulasi" di bagian atas untuk menghasilkan log uji forensik.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-between items-center text-xs text-stone-500">
                    <div>Menampilkan data halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</div>
                    <div>{{ $logs->links() }}</div>
                </div>
            </div>

            <!-- Footer Keamanan Siber -->
            <div class="p-4 bg-white rounded-2xl border border-stone-200 text-center text-xs text-stone-400">
                🛡️ Sistem Keamanan Siber Toko Buah Segar Luragung &bull; Dilindungi dengan CSRF Protection, Anti-IDOR, Sensitive Data Masking, dan Rate Limiting.
            </div>

        </div>
    </div>
</x-app-layout>
