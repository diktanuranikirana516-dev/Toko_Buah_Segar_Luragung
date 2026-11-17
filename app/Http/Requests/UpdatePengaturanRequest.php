<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanRequest extends FormRequest
{
    /**
     * Pastikan hanya Admin yang diizinkan memperbarui pengaturan toko (Broken Access Control Mitigation).
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()));
    }

    /**
     * Sanitasi input sebelum validasi untuk mencegah Stored XSS.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_toko'       => strip_tags(trim($this->input('nama_toko', ''))),
            'whatsapp'        => preg_replace('/[^0-9+]/', '', trim($this->input('whatsapp', ''))),
            'alamat'          => strip_tags(trim($this->input('alamat', ''))),
            'jam_operasional' => strip_tags(trim($this->input('jam_operasional', ''))),
        ]);
    }

    /**
     * Aturan validasi ketat untuk Pengaturan Toko.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_toko'       => ['required', 'string', 'max:255'],
            'whatsapp'        => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'alamat'          => ['nullable', 'string', 'max:500'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Pesan kesalahan validasi kustom.
     */
    public function messages(): array
    {
        return [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'whatsapp.required'  => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex'     => 'Nomor WhatsApp hanya boleh mengandung angka dan tanda plus (+).',
        ];
    }
}

