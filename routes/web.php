
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\TokoBuahController;
use App\Models\Produk;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK (CUSTOMER DAN ADMIN)
|--------------------------------------------------------------------------
| Halaman ini bisa dibuka oleh siapa saja,
| termasuk Admin yang sedang login.
|--------------------------------------------------------------------------
*/

// Halaman utama toko
Route::get('/', function () {

    $produk = Produk::where('stok', '>', 0)->latest()->get();
    if ($produk->isEmpty()) {
        $produk = Produk::all();
    }
    $pengaturan = Pengaturan::first();
    if ($pengaturan && in_array($pengaturan->whatsapp, ['6281234567890', '6285798089458'])) {
        $pengaturan->update(['whatsapp' => '6285861403683']);
    }

    return view('welcome', compact('produk', 'pengaturan'));

})->name('home');


// Katalog buah
Route::get('/katalog-buah', function () {

    $produk = Produk::latest()->get();

    return view('katalog_publik', compact('produk'));

})->name('katalog.publik');


// Halaman About
Route::get('/about', function () {

    $produk = Produk::latest()->take(6)->get();
    $pengaturan = Pengaturan::first();
    if ($pengaturan && in_array($pengaturan->whatsapp, ['6281234567890', '6285798089458'])) {
        $pengaturan->update(['whatsapp' => '6285861403683']);
    }

    return view('about', compact('produk', 'pengaturan'));

})->name('about');


// Halaman daftar produk
Route::get('/products', function (Request $request) {

    $search = strip_tags($request->input('search', ''));

    $query = Produk::query();

    if (!empty($search)) {
        $query->where('nama_produk', 'LIKE', "%{$search}%");
    }

    $produk = $query->latest()->paginate(8)->withQueryString();
    $pengaturan = Pengaturan::first();

    return view('products', compact('produk', 'search', 'pengaturan'));

})->name('products.index');


// Halaman Contact
Route::get('/contact', function () {

    $pengaturan = Pengaturan::first();

    return view('contact', compact('pengaturan'));

})->name('contact');


// Pengelolaan Keranjang Belanja Berbasis Sesi Pengguna (Session Cart)
Route::get('/cart/data', [TokoBuahController::class, 'getCart'])->name('cart.data');
Route::post('/cart/sync', [TokoBuahController::class, 'syncCart'])->name('cart.sync');
Route::post('/cart/add', [TokoBuahController::class, 'addToCart'])->name('cart.add');
Route::post('/keranjang/tambah', [TokoBuahController::class, 'addToCart'])->name('keranjang.tambah');
Route::post('/cart/update', [TokoBuahController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove/{id}', [TokoBuahController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [TokoBuahController::class, 'clearCart'])->name('cart.clear');

// Proses Pembelian Buah (Wajib Login & Multi-Metode Pembayaran)
Route::post('/checkout', [TokoBuahController::class, 'checkout'])
    ->name('checkout');


/*
|--------------------------------------------------------------------------
| DASHBOARD DAN PENGELOLAAN ADMIN
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PROFILE PENGGUNA (CUSTOMER DAN ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::get('/admin/profile', [ProfileController::class, 'edit'])
        ->name('admin.profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD DAN PENGELOLAAN KHUSUS ADMIN (ROLE-BASED ACCESS CONTROL)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');
        $stokMenipis = Produk::where('stok', '<=', 5)->count();
        $totalNilaiAset = Produk::all()->sum(function ($item) {
            return $item->harga * $item->stok;
        });
        $produkTerbaru = Produk::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'totalNilaiAset',
            'produkTerbaru'
        ));
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | KELOLA PRODUK ADMIN
    |--------------------------------------------------------------------------
    */

    Route::resource('produk', ProdukController::class);


    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENJUALAN ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', function () {
        $semuaProduk = Produk::all();
        $totalProduk = $semuaProduk->count();
        $totalStok = $semuaProduk->sum('stok');
        $totalNilaiAset = $semuaProduk->sum(function ($item) {
            return $item->harga * $item->stok;
        });

        return view('laporan', compact(
            'semuaProduk',
            'totalProduk',
            'totalStok',
            'totalNilaiAset'
        ));
    })->name('laporan.index');


    /*
    |--------------------------------------------------------------------------
    | PENGATURAN TOKO ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/pengaturan', function () {
        return app(PengaturanController::class)->edit();
    })->name('pengaturan.edit');

    Route::patch('/pengaturan', function (Request $request) {
        return app(PengaturanController::class)->update($request);
    })->name('pengaturan.update');


    // Riwayat Seluruh Transaksi Penjualan untuk Admin & Ekspor Laporan
    Route::get('/transaksi-admin', [TokoBuahController::class, 'adminIndexTransaksi'])
        ->name('admin.transaksi');
    Route::get('/transaksi-admin/export-csv', [TokoBuahController::class, 'exportTransaksiCsv'])
        ->name('admin.transaksi.export-csv');
    Route::post('/transaksi-admin/{id}/status', [TokoBuahController::class, 'updateStatus'])
        ->name('admin.transaksi.update-status');
    Route::patch('/transaksi-admin/{id}/status', [TokoBuahController::class, 'updateStatus']);

    // Audit Log Keamanan Siber untuk Admin & Ekspor
    Route::get('/audit-logs', [TokoBuahController::class, 'adminAuditLogs'])
        ->name('admin.audit-logs');
    Route::get('/audit-logs/export-csv', [TokoBuahController::class, 'exportAuditLogsCsv'])
        ->name('admin.audit-logs.export-csv');

    // Muat / Seed Data Simulasi Transaksi & Log Keamanan (Admin One-Click Tool)
    Route::post('/admin/seed-demo-data', [TokoBuahController::class, 'seedDemoData'])
        ->name('admin.seed-demo-data');

});


/*
|--------------------------------------------------------------------------
| REDIRECT LEGACY CUSTOMER ROUTES KE HOME (HOME-CENTRIC ARCHITECTURE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('home');
        })->name('dashboard');

        Route::get('/produk', function () {
            return redirect()->route('products.index');
        })->name('produk');

        Route::get('/transaksi', function () {
            return redirect()->route('home');
        })->name('transaksi');
    });


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
