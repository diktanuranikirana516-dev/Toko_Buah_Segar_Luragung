<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sanitasi input data sebelum validasi dijalankan (Defense in Depth).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => strip_tags(trim($this->input('name', ''))),
            'email' => strtolower(trim($this->input('email', ''))),
            'role'  => strtolower(trim($this->input('role', 'customer'))),
        ]);
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk pendaftaran.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Standar email:rfc,dns untuk production; fallback aman jika lingkungan offline
        $emailRule = app()->isProduction() ? 'email:rfc,dns' : 'email:rfc';

        return [
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'lowercase', $emailRule, 'max:255', 'unique:'.User::class],
            'password'             => ['required', 'confirmed', Password::defaults()],
            'role'                 => ['required', 'string', 'in:customer,admin'],
            'g-recaptcha-response' => [config('services.recaptcha.secret_key') ? 'required' : 'nullable', new ReCaptcha()],
        ];
    }

    /**
     * Pesan kustom untuk validasi registrasi.
     */
    public function messages(): array
    {
        return [
            'email.email'        => 'Format email tidak valid atau domain email tidak terdeteksi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required'      => 'Pilihan peran / hak akses akun wajib ditentukan.',
            'role.in'            => 'Pilihan peran akun tidak valid. Pilih Customer atau Admin.',
        ];
    }
}

