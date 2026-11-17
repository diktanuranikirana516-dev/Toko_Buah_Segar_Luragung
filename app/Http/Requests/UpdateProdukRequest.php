<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    /**
     * Otorisasi: Hanya Admin yang berhak memperbarui produk.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()));
    }

    /**
     * Sanitasi input data sebelum validasi untuk memitigasi XSS.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_produk' => strip_tags(trim($this->input('nama_produk', ''))),
            'deskripsi'   => strip_tags(trim($this->input('deskripsi', ''))),
        ]);
    }

    /**
     * Aturan validasi ketat untuk pembaruan produk.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255'],
            'harga'       => ['required', 'numeric', 'min:0', 'max:999999999'],
            'stok'        => ['required', 'integer', 'min:0', 'max:1000000'],
            'deskripsi'   => ['nullable', 'string', 'max:5000'],
            'gambar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan validasi kustom.
     */
    public function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk wajib diisi.',
            'harga.numeric'        => 'Harga produk harus berupa angka.',
            'harga.min'            => 'Harga produk tidak boleh bernilai negatif.',
            'stok.required'        => 'Stok produk wajib diisi.',
            'stok.integer'         => 'Stok produk harus berupa bilangan bulat.',
            'stok.min'             => 'Stok produk tidak boleh bernilai negatif.',
            'gambar.image'         => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'         => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max'           => 'Ukuran gambar maksimal adalah 2MB.',
        ];
    }
}

