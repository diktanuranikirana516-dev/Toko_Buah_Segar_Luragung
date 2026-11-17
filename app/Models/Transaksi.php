<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel fisik di database MySQL (fruitshop_luragung.transaksi)
     */
    protected $table = 'transaksi';

    /**
     * Perlindungan Mass Assignment (OWASP Mitigation):
     * Kolom dideklarasikan secara eksplisit.
     */
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status',
        'metode_pembayaran',
        'alamat_pengiriman',
        'nomor_telepon',
        'catatan',
    ];

    /**
     * Casting tipe data numerik
     */
    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'total_harga'  => 'decimal:2',
            'jumlah'       => 'integer',
        ];
    }

    /**
     * Relasi ke User / Customer yang memesan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Produk Buah yang dipesan.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
