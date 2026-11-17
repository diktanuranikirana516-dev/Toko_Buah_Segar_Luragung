<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    use HasFactory;

    /**
     * Perlindungan Mass Assignment (OWASP Mitigation).
     */
    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
    ];

    /**
     * Relasi ke transaksi penjualan buah.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'produk_id');
    }

    /**
     * Accessor: Integritas 100% Gambar & Aset (Anti-Broken Image).
     * Mencegah broken image pada seluruh antarmuka (Welcome, Admin, & Customer)
     * dengan normalisasi path storage dan fallback otomatis.
     */
    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar)) {
            // Jika atribut sudah berupa URL eksternal penuh
            if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
                return $this->gambar;
            }

            // Normalisasi pembersihan prefix berulang
            $clean = preg_replace('#^(public/|storage/|/storage/|/public/)#', '', ltrim($this->gambar, '/'));

            // 1. Cek di folder public/storage fisik (XAMPP / Windows)
            if (file_exists(public_path('storage/' . $clean))) {
                return asset('storage/' . $clean);
            }

            // 2. Cek di storage disk 'public' Laravel
            if (Storage::disk('public')->exists($clean)) {
                return asset('storage/' . $clean);
            }

            // 3. Cek di folder public/images
            if (file_exists(public_path('images/' . $clean))) {
                return asset('images/' . $clean);
            }

            // 4. Default return asset link
            return asset('storage/' . $clean);
        }

        // Fallback Inline SVG yang elegan, tidak akan pernah 404 walau offline
        return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="100%" height="100%" fill="%23fef3c7"/><text x="50%" y="45%" dominant-baseline="middle" text-anchor="middle" font-size="64">🍎</text><text x="50%" y="70%" dominant-baseline="middle" text-anchor="middle" fill="%23b45309" font-family="sans-serif" font-size="16" font-weight="bold">Buah Segar Luragung</text></svg>';
    }
}
