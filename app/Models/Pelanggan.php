<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    /**
     * Perlindungan Mass Assignment (OWASP Mitigation):
     * Mencegah injeksi parameter tersembunyi dengan mendeklarasikan kolom yang diizinkan secara eksplisit.
     */
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'nomor_telepon',
        'alamat',
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
