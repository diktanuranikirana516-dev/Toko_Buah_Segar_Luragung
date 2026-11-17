<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityAuditLog extends Model
{
    use HasFactory;

    protected $table = 'security_audit_logs';

    /**
     * Perlindungan Mass Assignment (OWASP Mitigation)
     */
    protected $fillable = [
        'user_id',
        'action',
        'event_type',
        'description',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat log keamanan siber secara aman (Sensitive Data Masking).
     */
    public static function record(string $eventType, string $description, ?array $details = null, ?int $userId = null): self
    {
        try {
            return self::create([
                'user_id'     => $userId ?? auth()->id(),
                'action'      => $eventType,
                'event_type'  => $eventType,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => substr(request()->userAgent() ?? '', 0, 500),
                'details'     => $details,
            ]);
        } catch (\Throwable $e) {
            // Fail safe: jangan hentikan flow aplikasi jika tabel log belum dimigrasi
            return new self();
        }
    }
}

