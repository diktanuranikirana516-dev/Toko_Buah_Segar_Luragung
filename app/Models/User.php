<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Kolom sensitif seperti 'role' TIDAK dimasukkan ke dalam $fillable
     * guna mencegah celah Mass Assignment Vulnerability.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke seluruh transaksi yang dilakukan oleh User ini.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    /**
     * Relasi ke profil data Pelanggan.
     */
    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'user_id');
    }

    /**
     * Helper untuk memeriksa apakah user memiliki hak akses admin.
     */
    public function isAdmin(): bool
    {
        return ($this->role ?? 'customer') === 'admin';
    }

    /**
     * Helper untuk memeriksa apakah user merupakan customer.
     */
    public function isCustomer(): bool
    {
        return ($this->role ?? 'customer') === 'customer';
    }
}
