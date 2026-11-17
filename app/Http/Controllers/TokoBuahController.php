<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TokoBuahController extends Controller
{
    /**
     * Dashboard untuk Customer (Ringkasan Belanja & Produk Rekomendasi).
     */
    public function customerDashboard(): View
    {
        $userId = auth()->id();

        $totalPesanan = Transaksi::where('user_id', $userId)->count();
        $totalBelanja = Transaksi::where('user_id', $userId)->where('status', 'selesai')->sum('total_harga');
        $transaksiTerbaru = Transaksi::with('produk')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $produk = Produk::where('stok', '>', 0)->latest()->take(8)->get();

        return view('customer.dashboard', compact(
            'totalPesanan',
            'totalBelanja',
            'transaksiTerbaru',
            'produk'
        ));
    }

    /**
     * Katalog Produk Khusus Customer dengan Pencarian Aman.
     */
    public function customerProduk(Request $request): View
    {
        $search = strip_tags(trim($request->input('search', '')));

        $query = Produk::query();

        if (!empty($search)) {
            $query->where('nama_produk', 'LIKE', "%{$search}%");
        }

        $produk = $query->latest()->paginate(8)->withQueryString();

        return view('customer.produk', compact('produk', 'search'));
    }

    /**
     * Ambil data keranjang belanja sesi pengguna aktif.
     * Jika pengunjung belum login (guest), otomatis mengembalikan keranjang kosong.
     */
    public function getCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'is_logged_in' => false,
                'cart'         => [],
                'cart_count'   => 0,
                'cart_total'   => 0,
            ]);
        }

        $userId = auth()->id();
        $cart = session()->get('cart_' . $userId, session()->get('cart', []));

        $cartCount = array_sum(array_column($cart, 'qty'));
        $cartTotal = array_sum(array_column($cart, 'subtotal'));

        return response()->json([
            'is_logged_in' => true,
            'cart'         => array_values($cart),
            'cart_count'   => $cartCount,
            'cart_total'   => $cartTotal,
        ]);
    }

    /**
     * Tambahkan buah ke dalam keranjang belanja (Session-based Cart).
     * Mendukung pemanggilan melalui form POST standard (@csrf) maupun AJAX JSON.
     */
    public function addToCart(Request $request)
    {
        // 1. Wajib Autentikasi Pengguna (Guest diarahkan ke login)
        if (!auth()->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => false,
                    'redirect' => route('login'),
                    'message'  => 'Silakan masuk ke akun Anda terlebih dahulu untuk menambah produk ke keranjang.',
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu untuk menambah produk ke keranjang.');
        }

        // 2. Validasi Input Produk & Kuantitas
        $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
            'jumlah'    => ['nullable', 'integer', 'min:1', 'max:1000'],
        ], [
            'produk_id.required' => 'Produk buah wajib dipilih.',
            'produk_id.exists'   => 'Produk buah tidak ditemukan di sistem.',
            'jumlah.min'         => 'Jumlah pembelian minimal 1 kg.',
        ]);

        $productId = (int) $request->input('produk_id');
        $jumlah = (int) $request->input('jumlah', 1);
        if ($jumlah < 1) {
            $jumlah = 1;
        }

        $produk = Produk::findOrFail($productId);

        if ($produk->stok <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, stok buah ' . ($produk->nama_produk ?? $produk->nama) . ' sedang kosong.',
                ], 422);
            }
            return back()->with('error', 'Maaf, stok buah ' . ($produk->nama_produk ?? $produk->nama) . ' sedang kosong.');
        }

        $userId = auth()->id();
        $cart = session()->get('cart_' . $userId, session()->get('cart', []));

        $namaBuah = $produk->nama_produk ?? $produk->nama ?? 'Buah Segar';
        $harga = (int) $produk->harga;
        $gambar = $produk->gambar_url;

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['qty'] + $jumlah;
            if ($newQty > $produk->stok) {
                $newQty = $produk->stok;
                $msg = "Maksimal stok tercapai: {$namaBuah} ({$produk->stok} kg).";
            } else {
                $msg = "✓ Kuantitas {$namaBuah} berhasil ditambah menjadi {$newQty} kg di keranjang!";
            }
            $cart[$productId]['qty'] = $newQty;
            $cart[$productId]['jumlah'] = $newQty;
            $cart[$productId]['subtotal'] = $newQty * $harga;
        } else {
            if ($jumlah > $produk->stok) {
                $jumlah = $produk->stok;
            }
            $cart[$productId] = [
                'id'              => $produk->id,
                'produk_id'       => $produk->id,
                'name'            => $namaBuah,
                'nama'            => $namaBuah,
                'price'           => $harga,
                'harga'           => $harga,
                'price_formatted' => number_format($harga, 0, ',', '.'),
                'stok'            => (int) $produk->stok,
                'image'           => $gambar,
                'gambar'          => $gambar,
                'qty'             => $jumlah,
                'jumlah'          => $jumlah,
                'subtotal'        => $jumlah * $harga,
            ];
            $msg = "✓ {$namaBuah} ({$jumlah} kg) berhasil masuk ke keranjang belanja!";
        }

        // Simpan ke sesi Laravel (Session Cart)
        session()->put('cart', $cart);
        session()->put('cart_' . $userId, $cart);

        $cartCount = array_sum(array_column($cart, 'qty'));
        $cartTotal = array_sum(array_column($cart, 'subtotal'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => $msg,
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart'       => array_values($cart),
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Update kuantitas buah di keranjang belanja.
     */
    public function updateCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Silakan masuk ke akun terlebih dahulu.'], 401);
        }

        $productId = (int) $request->input('produk_id', $request->input('id'));
        $delta = (int) $request->input('delta', 0);
        $qty = $request->has('qty') ? (int) $request->input('qty') : null;

        $userId = auth()->id();
        $cart = session()->get('cart_' . $userId, session()->get('cart', []));

        if (isset($cart[$productId])) {
            $currentQty = $cart[$productId]['qty'];
            $newQty = $qty !== null ? $qty : ($currentQty + $delta);

            if ($newQty <= 0) {
                unset($cart[$productId]);
            } else {
                $stok = $cart[$productId]['stok'] ?? 999;
                if ($newQty > $stok) {
                    $newQty = $stok;
                }
                $cart[$productId]['qty'] = $newQty;
                $cart[$productId]['jumlah'] = $newQty;
                $cart[$productId]['subtotal'] = $newQty * $cart[$productId]['price'];
            }

            session()->put('cart', $cart);
            session()->put('cart_' . $userId, $cart);
        }

        $cartCount = array_sum(array_column($cart, 'qty'));
        $cartTotal = array_sum(array_column($cart, 'subtotal'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart'       => array_values($cart),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Hapus buah tertentu dari keranjang belanja.
     */
    public function removeFromCart(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Silakan masuk ke akun terlebih dahulu.'], 401);
        }

        $userId = auth()->id();
        $cart = session()->get('cart_' . $userId, session()->get('cart', []));

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            session()->put('cart_' . $userId, $cart);
        }

        $cartCount = array_sum(array_column($cart, 'qty'));
        $cartTotal = array_sum(array_column($cart, 'subtotal'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil dihapus dari keranjang belanja.',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart'       => array_values($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang belanja.');
    }

    /**
     * Kosongkan seluruh keranjang belanja sesi aktif.
     */
    public function clearCart(Request $request)
    {
        if (auth()->check()) {
            session()->forget('cart_' . auth()->id());
        }
        session()->forget('cart');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Keranjang belanja telah dikosongkan.',
                'cart'       => [],
                'cart_count' => 0,
                'cart_total' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Keranjang belanja telah dikosongkan.');
    }

    /**
     * Sinkronisasi data keranjang ke sesi Laravel terikat user_id aktif (khusus user login).
     */
    public function syncCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masuk ke akun Anda terlebih dahulu.',
            ], 401);
        }

        $userId = auth()->id();
        $cartData = $request->input('cart', []);

        $sessionCart = [];
        if (is_array($cartData)) {
            foreach ($cartData as $it) {
                $pId = (int) ($it['id'] ?? $it['produk_id'] ?? 0);
                if ($pId > 0) {
                    $sessionCart[$pId] = $it;
                }
            }
        }

        session()->put('cart', $sessionCart);
        session()->put('cart_' . $userId, $sessionCart);

        return response()->json([
            'success' => true,
            'count'   => count($sessionCart),
        ]);
    }

    /**
     * Proses Checkout / Pembelian Buah dengan Perlindungan Race Condition (DB::transaction & lockForUpdate).
     * Mendukung pembelian langsung 1 buah maupun checkout beberapa buah sekaligus dari keranjang (Bayar di Tempat / COD).
     * Wajib login untuk keamanan autentikasi transaksi.
     */
    public function checkout(StoreTransaksiRequest $request): RedirectResponse
    {
        // Proteksi Autentikasi Pengguna: Tamu / Guest wajib login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu untuk menyelesaikan pesanan buah.');
        }

        $validated = $request->validated();
        $user = auth()->user();
        $userId = $user->id;
        $namaPemesan = $user->name ?? (!empty($validated['nama_pemesan']) ? $validated['nama_pemesan'] : 'Pelanggan');
        $metodePembayaran = $validated['metode_pembayaran'] ?? 'COD';

        // Tentukan daftar item yang akan diproses (multi-item keranjang atau single item)
        $itemsToProcess = [];
        if (!empty($validated['parsed_items']) && is_array($validated['parsed_items'])) {
            $itemsToProcess = $validated['parsed_items'];
        } elseif (!empty($validated['produk_id'])) {
            $itemsToProcess[] = [
                'produk_id' => (int) $validated['produk_id'],
                'jumlah'    => (int) ($validated['jumlah'] ?? 1),
            ];
        }

        if (empty($itemsToProcess)) {
            return back()->withInput()->with('error', 'Keranjang belanja Anda kosong. Silakan pilih minimal 1 produk buah.');
        }

        try {
            $orderResult = DB::transaction(function () use ($itemsToProcess, $validated, $user, $userId, $namaPemesan, $metodePembayaran) {
                // Satu kode transaksi unik yang menaungi seluruh buah dalam satu checkout
                $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $orderItems = [];
                $grandTotal = 0;
                $totalKg = 0;

                foreach ($itemsToProcess as $item) {
                    $pId = (int) ($item['produk_id'] ?? 0);
                    $qty = (int) ($item['jumlah'] ?? 1);

                    if ($qty <= 0) continue;

                    // Kunci baris data produk untuk mencegah manipulasi stok secara paralel (Race Condition)
                    $produk = Produk::where('id', $pId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Validasi kecukupan stok saat transaksi diproses
                    if ($produk->stok < $qty) {
                        throw new \Exception("Maaf, stok buah '{$produk->nama_produk}' tidak mencukupi (tersisa {$produk->stok} kg).");
                    }

                    // Kurangi stok produk secara atomik
                    $produk->decrement('stok', $qty);

                    $subtotal = (float) $produk->harga * $qty;
                    $grandTotal += $subtotal;
                    $totalKg += $qty;

                    // Buat record Transaksi untuk masing-masing komoditas buah
                    Transaksi::create([
                        'kode_transaksi'    => $kodeTransaksi,
                        'user_id'           => $userId,
                        'produk_id'         => $produk->id,
                        'jumlah'            => $qty,
                        'harga_satuan'      => $produk->harga,
                        'total_harga'       => $subtotal,
                        'status'            => 'diproses',
                        'metode_pembayaran' => $metodePembayaran,
                        'alamat_pengiriman' => $validated['alamat_pengiriman'],
                        'nomor_telepon'     => $validated['nomor_telepon'],
                        'catatan'           => !empty($validated['catatan']) ? $validated['catatan'] : 'Metode: ' . $metodePembayaran,
                    ]);

                    $orderItems[] = [
                        'nama'     => $produk->nama_produk,
                        'qty'      => $qty,
                        'harga'    => $produk->harga,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($orderItems)) {
                    throw new \Exception('Tidak ada komoditas buah yang valid untuk diproses.');
                }

                // Sinkronisasi data ke profil Pelanggan
                if ($userId) {
                    Pelanggan::updateOrCreate(
                        ['user_id' => $userId],
                        [
                            'nama'          => $namaPemesan,
                            'email'         => $user->email,
                            'nomor_telepon' => $validated['nomor_telepon'],
                            'alamat'        => $validated['alamat_pengiriman'],
                        ]
                    );
                } else {
                    Pelanggan::create([
                        'nama'          => $namaPemesan,
                        'email'         => null,
                        'nomor_telepon' => $validated['nomor_telepon'],
                        'alamat'        => $validated['alamat_pengiriman'],
                    ]);
                }

                return [
                    'kode'        => $kodeTransaksi,
                    'items'       => $orderItems,
                    'grand_total' => $grandTotal,
                    'total_kg'    => $totalKg,
                    'metode'      => $metodePembayaran,
                ];
            });

            // Catat log aktivitas transaksi yang sukses tanpa informasi sensitif
            Log::info('Transaksi pembelian buah berhasil dibuat', [
                'kode_transaksi' => $orderResult['kode'],
                'user_id'        => $userId,
                'total_harga'    => $orderResult['grand_total'],
                'total_items'    => count($orderResult['items']),
                'metode'         => $orderResult['metode'],
            ]);

            \App\Models\SecurityAuditLog::record(
                'TRANSACTION_CREATED',
                "Pesanan buah ({$orderResult['kode']}) dibuat - " . count($orderResult['items']) . " komoditas (Total: Rp " . number_format($orderResult['grand_total'], 0, ',', '.') . ") - {$orderResult['metode']}",
                [
                    'kode'        => $orderResult['kode'],
                    'total'       => $orderResult['grand_total'],
                    'total_items' => count($orderResult['items']),
                    'total_kg'    => $orderResult['total_kg'],
                    'metode'      => $orderResult['metode'],
                ],
                $userId
            );

            // Format Pesan WhatsApp Rinci untuk Konfirmasi Admin
            $storeName = 'Toko Buah Segar Luragung';
            $itemLines = [];
            foreach ($orderResult['items'] as $idx => $oi) {
                $itemLines[] = ($idx + 1) . ". {$oi['nama']} ({$oi['qty']} kg x Rp " . number_format($oi['harga'], 0, ',', '.') . " = Rp " . number_format($oi['subtotal'], 0, ',', '.') . ")";
            }
            $daftarBuah = implode("%0A", $itemLines);

            $waMsg = "Halo Admin {$storeName}, saya telah memesan buah via website dengan rincian:%0A"
                   . "• Kode Transaksi: {$orderResult['kode']}%0A"
                   . "• Pemesan: {$namaPemesan}%0A"
                   . "• Buah yang Dipesan:%0A{$daftarBuah}%0A"
                   . "• Total Tagihan: Rp " . number_format($orderResult['grand_total'], 0, ',', '.') . "%0A"
                   . "• Metode Pembayaran: {$orderResult['metode']}%0A"
                   . "• Alamat Pengantaran: {$validated['alamat_pengiriman']}%0A"
                   . "• WhatsApp: {$validated['nomor_telepon']}%0A"
                   . (!empty($validated['catatan']) ? "• Catatan: {$validated['catatan']}%0A" : "")
                   . "Mohon segera diproses ya admin. Terima kasih!";

            // Kosongkan sesi keranjang user yang telah berhasil checkout
            session()->forget('cart_' . $userId);

            // Tetap di halaman asal dengan modal struk sukses
            return back()->with('order_success', [
                'kode'        => $orderResult['kode'],
                'nama'        => $namaPemesan,
                'items'       => $orderResult['items'],
                'total_items' => count($orderResult['items']),
                'total_qty'   => $orderResult['total_kg'],
                'total'       => number_format($orderResult['grand_total'], 0, ',', '.'),
                'metode'      => $orderResult['metode'],
                'alamat'      => $validated['alamat_pengiriman'],
                'telepon'     => $validated['nomor_telepon'],
                'catatan'     => !empty($validated['catatan']) ? $validated['catatan'] : 'Metode: ' . $orderResult['metode'],
                'wa_msg'      => $waMsg,
                'waktu'       => date('d/m/Y H:i') . ' WIB',
            ])->with('success', "Pesanan " . count($orderResult['items']) . " jenis buah berhasil dibuat via {$orderResult['metode']}! Pesanan Anda segera diproses.");

        } catch (\Throwable $e) {
            Log::error('Kegagalan saat memproses transaksi: ' . $e->getMessage(), [
                'user_id' => $userId,
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Riwayat Transaksi Khusus Customer (Proteksi IDOR: Hanya menampilkan data milik user sendiri).
     */
    public function riwayatTransaksi(): View
    {
        $transaksis = Transaksi::with('produk')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.transaksi', compact('transaksis'));
    }

    /**
     * Kelola Transaksi untuk Admin (Melihat seluruh data penjualan buah).
     */
    public function adminIndexTransaksi(Request $request): View
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        // Auto-seed data simulasi jika tabel transaksi masih kosong
        if (Transaksi::count() === 0) {
            (new \Database\Seeders\DummyDataSeeder())->run();
        }

        // Filter status jika dipilih
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = Transaksi::with(['user', 'produk']);

        if (!empty($statusFilter) && in_array($statusFilter, ['selesai', 'diproses', 'pending', 'menunggu', 'dibatalkan'])) {
            if (in_array($statusFilter, ['pending', 'menunggu'])) {
                $query->whereIn('status', ['pending', 'menunggu']);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('produk', function($pq) use ($search) {
                      $pq->where('nama_produk', 'LIKE', "%{$search}%");
                  });
            });
        }

        $transaksis = $query->latest()->paginate(15)->withQueryString();

        // Statistik Penjualan
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::where('status', 'selesai')->sum('total_harga');
        $totalVolume = Transaksi::where('status', 'selesai')->sum('jumlah');
        $transaksiSelesai = Transaksi::where('status', 'selesai')->count();
        $persenSelesai = $totalTransaksi > 0 ? round(($transaksiSelesai / $totalTransaksi) * 100) : 0;

        return view('admin.transaksi', compact(
            'transaksis',
            'totalTransaksi',
            'totalPendapatan',
            'totalVolume',
            'persenSelesai',
            'statusFilter',
            'search'
        ));
    }

    /**
     * Memperbarui status pesanan transaksi penjualan buah oleh Admin.
     * Pilihan status: pending, diproses, selesai, dibatalkan.
     * Setiap perubahan status dicatat ke SecurityAuditLog.
     */
    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini khusus administrator.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,menunggu,diproses,selesai,dibatalkan'],
        ], [
            'status.required' => 'Status transaksi wajib dipilih.',
            'status.in'       => 'Pilihan status tidak valid. Pilih antara pending, diproses, selesai, atau dibatalkan.',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $oldStatus = $transaksi->status;
        $newStatus = $validated['status'];
        if ($newStatus === 'menunggu') {
            $newStatus = 'pending';
        }

        if ($oldStatus === $newStatus) {
            return back()->with('info', "Status transaksi {$transaksi->kode_transaksi} tidak berubah (masih '{$newStatus}').");
        }

        // Jika transaksi memiliki kode_transaksi bersama (multi-item), perbarui seluruh item pesanan tersebut
        if (!empty($transaksi->kode_transaksi)) {
            $updatedCount = Transaksi::where('kode_transaksi', $transaksi->kode_transaksi)->update(['status' => $newStatus]);
        } else {
            $transaksi->update(['status' => $newStatus]);
            $updatedCount = 1;
        }

        // Catat Audit Keamanan Siber
        \App\Models\SecurityAuditLog::record(
            'TRANSACTION_STATUS_UPDATED',
            "Status pesanan {$transaksi->kode_transaksi} ({$updatedCount} item) diubah dari '{$oldStatus}' menjadi '{$newStatus}' oleh admin " . auth()->user()->name,
            [
                'transaksi_id'   => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'old_status'     => $oldStatus,
                'new_status'     => $newStatus,
                'items_count'    => $updatedCount,
                'admin_id'       => auth()->id(),
                'admin_name'     => auth()->user()->name,
                'admin_email'    => auth()->user()->email,
                'ip'             => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ],
            auth()->id()
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => "✓ Status transaksi {$transaksi->kode_transaksi} berhasil diubah menjadi '" . ucfirst($newStatus) . "'.",
                'new_status' => $newStatus,
            ]);
        }

        return back()->with('success', "✓ Status transaksi {$transaksi->kode_transaksi} berhasil diperbarui menjadi '" . ucfirst($newStatus) . "'.");
    }

    /**
     * Tampilkan Halaman Audit Keamanan Siber Khusus Admin
     */
    public function adminAuditLogs(Request $request): View
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        // Auto-seed log keamanan jika tabel audit masih kosong
        if (\App\Models\SecurityAuditLog::count() === 0) {
            (new \Database\Seeders\DummyDataSeeder())->run();
        }

        $eventFilter = $request->query('event');
        $search = $request->query('search');

        $query = \App\Models\SecurityAuditLog::with('user');

        if (!empty($eventFilter)) {
            $query->where('event_type', $eventFilter);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('event_type', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        // Statistik Keamanan
        $totalLogs = \App\Models\SecurityAuditLog::count();
        $loginSuccessCount = \App\Models\SecurityAuditLog::where('event_type', 'LOGIN_SUCCESS')->count();
        $failedAttempts = \App\Models\SecurityAuditLog::whereIn('event_type', ['LOGIN_FAILED', 'LOGIN_LOCKOUT'])->count();
        $activeUsersCount = \App\Models\User::count();

        return view('admin.audit_logs', compact(
            'logs',
            'totalLogs',
            'loginSuccessCount',
            'failedAttempts',
            'activeUsersCount',
            'eventFilter',
            'search'
        ));
    }

    /**
     * Fitur Admin: Muat Data Simulasi Transaksi & Log Keamanan (One-Click Seeder)
     */
    public function seedDemoData(): RedirectResponse
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        (new \Database\Seeders\DummyDataSeeder())->run();

        \App\Models\SecurityAuditLog::record(
            'DEMO_DATA_SEEDED',
            'Admin memuat 12+ data simulasi transaksi penjualan dan log forensik audit keamanan',
            ['total_records_generated' => 25],
            auth()->id()
        );

        return back()->with('success', 'Data transaksi buah dan rekaman audit keamanan berhasil diisi dengan data simulasi yang realistis!');
    }

    /**
     * Ekspor Laporan Data Transaksi Penjualan ke CSV
     */
    public function exportTransaksiCsv()
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $transaksis = Transaksi::with(['user', 'produk'])->latest()->get();
        $fileName = 'Laporan_Transaksi_Buah_' . date('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Kode Transaksi', 'Nama Pembeli', 'Email', 'Produk Buah', 'Qty (kg)', 'Harga Satuan (Rp)', 'Total Bayar (Rp)', 'Status', 'Metode Pembayaran', 'Alamat Pengiriman', 'Nomor Telepon', 'Tanggal'];

        $callback = function() use ($transaksis, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns, ';');

            foreach ($transaksis as $t) {
                fputcsv($file, [
                    $t->kode_transaksi,
                    $t->user->name ?? 'Pelanggan Umum',
                    $t->user->email ?? '-',
                    $t->produk->nama_produk ?? 'Buah Segar',
                    $t->jumlah,
                    $t->harga_satuan,
                    $t->total_harga,
                    ucfirst($t->status),
                    $t->metode_pembayaran ?? 'COD',
                    $t->alamat_pengiriman,
                    $t->nomor_telepon,
                    $t->created_at->format('d/m/Y H:i')
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Laporan Audit Log Keamanan Siber ke CSV
     */
    public function exportAuditLogsCsv()
    {
        if (auth()->user()->role !== 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $logs = \App\Models\SecurityAuditLog::with('user')->latest()->get();
        $fileName = 'Laporan_Audit_Logs_' . date('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Waktu (WIB)', 'Event Type', 'Deskripsi Keamanan', 'Akun Pengguna', 'Alamat IP', 'User Agent'];

        $callback = function() use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns, ';');

            foreach ($logs as $l) {
                fputcsv($file, [
                    $l->id,
                    $l->created_at->format('d/m/Y H:i:s'),
                    $l->event_type ?? $l->action,
                    $l->description,
                    $l->user->name ?? ($l->user_id ? "User #{$l->user_id}" : 'Sistem / Anonim'),
                    $l->ip_address,
                    $l->user_agent
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
