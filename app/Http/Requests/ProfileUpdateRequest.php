<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Sanitasi data input sebelum validasi dijalankan (Anti-XSS).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => strip_tags(trim($this->input('name', ''))),
            'email' => strtolower(trim($this->input('email', ''))),
        ]);
    }

    /**
     * Aturan validasi profil.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
