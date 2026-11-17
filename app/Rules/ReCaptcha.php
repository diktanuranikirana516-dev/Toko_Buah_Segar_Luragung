<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        // Jika secret key belum dikonfigurasi pada .env (misalnya pada lokal dev),
        // bypass otomatis agar tidak merusak alur pengujian (Zero Breaking Changes).
        if (empty($secretKey)) {
            return;
        }

        if (empty($value)) {
            $fail('Verifikasi bot (reCAPTCHA) wajib diisi.');
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! ($response->json('success') ?? false)) {
                $fail('Verifikasi reCAPTCHA gagal atau kadaluarsa. Silakan ulangi.');
            }
        } catch (\Throwable $e) {
            Log::error('Koneksi verifikasi reCAPTCHA gagal: ' . $e->getMessage());
            // Fail open atau fail secure tergantung environment, namun beri pesan aman ke user
            $fail('Gagal memverifikasi reCAPTCHA dari server. Silakan coba lagi beberapa saat lagi.');
        }
    }
}

