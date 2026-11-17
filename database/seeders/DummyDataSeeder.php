<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\SecurityAuditLog;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds for Realistic Transactions and Security Audit Logs.
     */
    public function run(): void
    {
        // 1. Pastikan Komoditas Buah Tersedia
        $daftarBuah = [
            [
                'nama_produk' => 'Apel Fuji Super Manis',
                'harga'       => 35000,
                'stok'        => 50,
                'deskripsi'   => 'Apel fuji pilihan impor grade A dengan tekstur renyah, berair, dan rasa manis alami yang menyegarkan.',
                'gambar'      => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Mangga Harum Manis Matang Pohon',
                'harga'       => 28000,
                'stok'        => 40,
                'deskripsi'   => 'Mangga harum manis asli dengan daging buah tebal, aroma harum khas, dan rasa manis legit sempurna.',
                'gambar'      => 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Jeruk Medan Segar Pilihan',
                'harga'       => 25000,
                'stok'        => 65,
                'deskripsi'   => 'Jeruk segar kaya vitamin C, kulit tipis, bulir padat banyak air, manis asam segar alami.',
                'gambar'      => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Pisang Cavendish Sunpride',
                'harga'       => 22000,
                'stok'        => 45,
                'deskripsi'   => 'Pisang cavendish kulit mulus bersih, daging lembut bernutrisi tinggi, cocok untuk sarapan dan bekal keluarga.',
                'gambar'      => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Alpukat Mentega Super Creamy',
                'harga'       => 32000,
                'stok'        => 30,
                'deskripsi'   => 'Alpukat mentega tanpa serat dengan daging tebal legit, cocok untuk jus alpukat, es campur, atau diet sehat.',
                'gambar'      => 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Buah Naga Merah Manis',
                'harga'       => 20000,
                'stok'        => 35,
                'deskripsi'   => 'Buah naga daging merah pekat kaya antioksidan dan serat tinggi, rasa manis alami segar.',
                'gambar'      => 'https://images.unsplash.com/photo-1527325678964-54921661f888?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Anggur Red Globe Manis Tanpa Biji',
                'harga'       => 65000,
                'stok'        => 25,
                'deskripsi'   => 'Anggur merah butiran besar, tekstur renyah kres, rasa manis legit dan sangat disukai anak-anak.',
                'gambar'      => 'https://images.unsplash.com/photo-1537640538966-79f369143f8f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama_produk' => 'Melon Madu Sweet Honey',
                'harga'       => 18000,
                'stok'        => 20,
                'deskripsi'   => 'Melon madu daging tebal oranye kehijauan, aroma harum wangi dan tingkat kemanisan tinggi.',
                'gambar'      => 'https://images.unsplash.com/photo-1571575179703-4bde44fb1403?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $produkModels = [];
        foreach ($daftarBuah as $b) {
            $prod = Produk::firstOrCreate(
                ['nama_produk' => $b['nama_produk']],
                [
                    'harga'     => $b['harga'],
                    'stok'      => $b['stok'],
                    'deskripsi' => $b['deskripsi'],
                    'gambar'    => $b['gambar'],
                ]
            );
            $produkModels[] = $prod;
        }

        // 2. Pastikan Akun Pelanggan Tersedia
        $daftarPelanggan = [
            [
                'name'  => 'Prianta Umbara',
                'email' => 'priantaumbara@gmail.com',
                'phone' => '085861403683',
                'addr'  => 'Jl. Raya Luragung - Cibingbin No. 42, Kec. Luragung, Kuningan',
                'role'  => 'admin',
            ],
            [
                'name'  => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'phone' => '081298765432',
                'addr'  => 'Perumahan Luragung Permai Blok B2 No. 10, Kuningan',
                'role'  => 'customer',
            ],
            [
                'name'  => 'Budi Santoso',
                'email' => 'budi.santoso@yahoo.com',
                'phone' => '087712345678',
                'addr'  => 'Dusun Puhun RT 03/RW 01, Desa Luragunglandeuh, Kec. Luragung',
                'role'  => 'customer',
            ],
            [
                'name'  => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'phone' => '085698712345',
                'addr'  => 'Jl. Siliwangi No. 108, Cijoho, Kab. Kuningan',
                'role'  => 'customer',
            ],
            [
                'name'  => 'Andi Wijaya',
                'email' => 'andi.wijaya@outlook.com',
                'phone' => '081345678901',
                'addr'  => 'Komp. Pasar Galuh Luragung Kios No. 14, Kuningan',
                'role'  => 'customer',
            ],
            [
                'name'  => 'Rina Marlina',
                'email' => 'rina.marlina@gmail.com',
                'phone' => '082198765432',
                'addr'  => 'Desa Margasari RT 05/RW 02, Kec. Luragung',
                'role'  => 'customer',
            ],
        ];

        $userModels = [];
        foreach ($daftarPelanggan as $p) {
            $user = User::firstOrCreate(
                ['email' => $p['email']],
                [
                    'name'     => $p['name'],
                    'password' => Hash::make('password123'),
                ]
            );
            $user->forceFill(['role' => $p['role']])->save();
            $userModels[] = $user;

            Pelanggan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama'          => $p['name'],
                    'email'         => $p['email'],
                    'nomor_telepon' => $p['phone'],
                    'alamat'        => $p['addr'],
                ]
            );
        }

        // 3. Buat Data Transaksi Penjualan Buah Realistis
        $dataTransaksi = [
            [
                'user_idx'   => 1, // Siti
                'prod_idx'   => 0, // Apel Fuji
                'qty'        => 3,
                'status'     => 'selesai',
                'catatan'    => 'Mohon dipilihkan apel yang keras manis dan tidak memar ya.',
                'created_at' => Carbon::now()->subMinutes(25),
            ],
            [
                'user_idx'   => 2, // Budi
                'prod_idx'   => 1, // Mangga
                'qty'        => 5,
                'status'     => 'selesai',
                'catatan'    => 'Pesanan untuk oleh-oleh keluarga di Cirebon.',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_idx'   => 0, // Prianta
                'prod_idx'   => 6, // Anggur Red Globe
                'qty'        => 2,
                'status'     => 'selesai',
                'catatan'    => 'Packing kotak mika rapi ya min.',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'user_idx'   => 3, // Dewi
                'prod_idx'   => 4, // Alpukat
                'qty'        => 4,
                'status'     => 'selesai',
                'catatan'    => 'Pilih yang sudah matang 2 buah, yang 2 buah lagi agak mengkal.',
                'created_at' => Carbon::now()->subHours(18),
            ],
            [
                'user_idx'   => 4, // Andi
                'prod_idx'   => 2, // Jeruk Medan
                'qty'        => 10,
                'status'     => 'selesai',
                'catatan'    => 'Pesanan grosir untuk acara rapat kantor desa.',
                'created_at' => Carbon::now()->subDays(1)->subHours(3),
            ],
            [
                'user_idx'   => 5, // Rina
                'prod_idx'   => 3, // Pisang Cavendish
                'qty'        => 3,
                'status'     => 'selesai',
                'catatan'    => 'Tolong pilih sisir pisang yang mulus kuning cerah.',
                'created_at' => Carbon::now()->subDays(1)->subHours(8),
            ],
            [
                'user_idx'   => 1, // Siti
                'prod_idx'   => 5, // Buah Naga
                'qty'        => 4,
                'status'     => 'diproses',
                'catatan'    => 'Kirim sore hari ya sekitar jam 4 saat saya sudah di rumah.',
                'created_at' => Carbon::now()->subDays(2)->subHours(1),
            ],
            [
                'user_idx'   => 2, // Budi
                'prod_idx'   => 7, // Melon Madu
                'qty'        => 2,
                'status'     => 'diproses',
                'catatan'    => 'Tolong dipilih yang paling wangi dan manis.',
                'created_at' => Carbon::now()->subDays(2)->subHours(6),
            ],
            [
                'user_idx'   => 3, // Dewi
                'prod_idx'   => 0, // Apel Fuji
                'qty'        => 2,
                'status'     => 'selesai',
                'catatan'    => null,
                'created_at' => Carbon::now()->subDays(3)->subHours(4),
            ],
            [
                'user_idx'   => 4, // Andi
                'prod_idx'   => 1, // Mangga
                'qty'        => 3,
                'status'     => 'selesai',
                'catatan'    => 'Pesanan rutin mingguan.',
                'created_at' => Carbon::now()->subDays(4)->subHours(2),
            ],
            [
                'user_idx'   => 5, // Rina
                'prod_idx'   => 6, // Anggur Red Globe
                'qty'        => 1,
                'status'     => 'selesai',
                'catatan'    => 'Untuk parcel buah menjenguk kerabat sakit.',
                'created_at' => Carbon::now()->subDays(5)->subHours(7),
            ],
            [
                'user_idx'   => 1, // Siti
                'prod_idx'   => 2, // Jeruk Medan
                'qty'        => 3,
                'status'     => 'menunggu',
                'catatan'    => 'Konfirmasi via WA admin ya.',
                'created_at' => Carbon::now()->subMinutes(10),
            ],
        ];

        foreach ($dataTransaksi as $i => $item) {
            $user = $userModels[$item['user_idx']] ?? $userModels[0];
            $prod = $produkModels[$item['prod_idx']] ?? $produkModels[0];

            $dateCode = $item['created_at']->format('Ymd');
            $randHex  = strtoupper(substr(md5($i . $dateCode), 0, 6));
            $kodeTrx  = "TRX-{$dateCode}-{$randHex}";

            $pelanggan = Pelanggan::where('user_id', $user->id)->first();
            $total     = $prod->harga * $item['qty'];

            $metodeList = ['COD', 'Transfer Bank BCA', 'Transfer Bank Mandiri', 'E-Wallet GoPay', 'E-Wallet OVO'];
            $metodeDipilih = $metodeList[$i % count($metodeList)];

            Transaksi::updateOrCreate(
                ['kode_transaksi' => $kodeTrx],
                [
                    'user_id'           => $user->id,
                    'produk_id'         => $prod->id,
                    'jumlah'            => $item['qty'],
                    'harga_satuan'      => $prod->harga,
                    'total_harga'       => $total,
                    'status'            => $item['status'],
                    'metode_pembayaran' => $metodeDipilih,
                    'alamat_pengiriman' => $pelanggan?->alamat ?? 'Jl. Raya Luragung No. 1, Kuningan',
                    'nomor_telepon'     => $pelanggan?->nomor_telepon ?? '085861403683',
                    'catatan'           => $item['catatan'],
                    'created_at'        => $item['created_at'],
                    'updated_at'        => $item['created_at'],
                ]
            );
        }

        // 4. Buat Rekaman Security Audit Logs (Keamanan Siber OWASP)
        $auditLogs = [
            [
                'event_type'  => 'LOGIN_SUCCESS',
                'description' => 'Admin Prianta Umbara berhasil login ke sistem dengan autentikasi aman',
                'ip_address'  => '127.0.0.1',
                'user_idx'    => 0,
                'details'     => ['guard' => 'web', 'role' => 'admin', 'auth_method' => 'password_recaptcha'],
                'created_at'  => Carbon::now()->subMinutes(5),
            ],
            [
                'event_type'  => 'TRANSACTION_CREATED',
                'description' => 'Pesanan buah baru dibuat oleh pelanggan Siti Nurhaliza (TRX-' . Carbon::now()->format('Ymd') . '-...)',
                'ip_address'  => '192.168.1.45',
                'user_idx'    => 1,
                'details'     => ['komoditas' => 'Jeruk Medan Segar', 'jumlah_kg' => 3, 'metode_order' => 'web_checkout'],
                'created_at'  => Carbon::now()->subMinutes(10),
            ],
            [
                'event_type'  => 'LOGIN_SUCCESS',
                'description' => 'Pelanggan Siti Nurhaliza berhasil masuk ke dashboard belanja',
                'ip_address'  => '192.168.1.45',
                'user_idx'    => 1,
                'details'     => ['guard' => 'web', 'role' => 'customer', 'session_id' => 'sess_masked_***'],
                'created_at'  => Carbon::now()->subMinutes(12),
            ],
            [
                'event_type'  => 'LOGIN_FAILED',
                'description' => 'Percobaan login gagal terdeteksi (Kredensial tidak cocok untuk bud***@yahoo.com) [Mitigasi Brute Force OWASP]',
                'ip_address'  => '192.168.1.115',
                'user_idx'    => null,
                'details'     => ['reason' => 'invalid_credentials', 'rate_limit_hits' => 1, 'masked_email' => 'bud***@yahoo.com'],
                'created_at'  => Carbon::now()->subHours(1),
            ],
            [
                'event_type'  => 'LOGIN_LOCKOUT',
                'description' => 'Sistem Rate Limiter memblokir sementara IP 192.168.1.115 karena 5x percobaan gagal (Mitigasi Serangan DoS / Brute-force)',
                'ip_address'  => '192.168.1.115',
                'user_idx'    => null,
                'details'     => ['lockout_seconds' => 60, 'ip_blocked' => '192.168.1.115', 'action' => 'throttle_applied'],
                'created_at'  => Carbon::now()->subHours(1)->addMinutes(2),
            ],
            [
                'event_type'  => 'TRANSACTION_CREATED',
                'description' => 'Checkout buah Apel Fuji Super berhasil diproses untuk pelanggan Budi Santoso',
                'ip_address'  => '192.168.1.78',
                'user_idx'    => 2,
                'details'     => ['total_harga' => 105000, 'status' => 'selesai', 'payment' => 'cash_on_delivery'],
                'created_at'  => Carbon::now()->subHours(2),
            ],
            [
                'event_type'  => 'REGISTER',
                'description' => 'Pendaftaran akun pelanggan baru berhasil diverifikasi (Rina Marlina)',
                'ip_address'  => '192.168.1.92',
                'user_idx'    => 5,
                'details'     => ['role' => 'customer', 'recaptcha_verified' => true, 'email' => 'rin***@gmail.com'],
                'created_at'  => Carbon::now()->subHours(10),
            ],
            [
                'event_type'  => 'UPDATE_PROFILE',
                'description' => 'Pelanggan Dewi Lestari memperbarui nomor kontak WhatsApp pengiriman',
                'ip_address'  => '192.168.1.66',
                'user_idx'    => 3,
                'details'     => ['field_updated' => 'nomor_telepon', 'verified' => true],
                'created_at'  => Carbon::now()->subHours(15),
            ],
            [
                'event_type'  => 'PRODUCT_UPDATE',
                'description' => 'Admin Prianta Umbara memperbarui kuota stok komoditas buah Mangga Harum Manis (+20 kg)',
                'ip_address'  => '127.0.0.1',
                'user_idx'    => 0,
                'details'     => ['produk_id' => 2, 'stok_lama' => 20, 'stok_baru' => 40],
                'created_at'  => Carbon::now()->subDays(1),
            ],
            [
                'event_type'  => 'EXPORT_REPORT',
                'description' => 'Admin mengunduh laporan rekapitulasi data penjualan toko buah (Ekspor Laporan PDF/Cetak)',
                'ip_address'  => '127.0.0.1',
                'user_idx'    => 0,
                'details'     => ['module' => 'Laporan Transaksi', 'format' => 'PDF', 'scope' => 'all_records'],
                'created_at'  => Carbon::now()->subDays(1)->subHours(4),
            ],
            [
                'event_type'  => 'CSRF_TOKEN_VERIFIED',
                'description' => 'Validasi integritas CSRF Token berhasil diamankan saat proses submit transaksi',
                'ip_address'  => '192.168.1.33',
                'user_idx'    => 4,
                'details'     => ['owasp_standard' => 'A01_Broken_Access_Control', 'status' => 'token_valid'],
                'created_at'  => Carbon::now()->subDays(2),
            ],
            [
                'event_type'  => 'LOGIN_SUCCESS',
                'description' => 'Admin Prianta Umbara berhasil login ke dashboard admin',
                'ip_address'  => '127.0.0.1',
                'user_idx'    => 0,
                'details'     => ['guard' => 'web', 'role' => 'admin', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'],
                'created_at'  => Carbon::now()->subDays(2)->subHours(3),
            ],
            [
                'event_type'  => 'SECURITY_AUDIT_VIEW',
                'description' => 'Admin memeriksa log audit forensik keamanan siber sistem toko buah',
                'ip_address'  => '127.0.0.1',
                'user_idx'    => 0,
                'details'     => ['compliance' => 'OWASP_ASVS_Level_2', 'records_reviewed' => 20],
                'created_at'  => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($auditLogs as $log) {
            $user = isset($log['user_idx']) && isset($userModels[$log['user_idx']]) 
                ? $userModels[$log['user_idx']] 
                : null;

            SecurityAuditLog::firstOrCreate(
                [
                    'event_type'  => $log['event_type'],
                    'description' => $log['description'],
                    'created_at'  => $log['created_at'],
                ],
                [
                    'action'      => $log['event_type'],
                    'user_id'     => $user?->id,
                    'ip_address'  => $log['ip_address'],
                    'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
                    'details'     => $log['details'],
                    'updated_at'  => $log['created_at'],
                ]
            );
        }
    }
}
