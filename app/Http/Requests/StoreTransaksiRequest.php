<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Otorisasi: Dapat diakses oleh pembeli umum maupun customer terdaftar.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sanitasi input data sebelum validasi (Defense in Depth / Anti-XSS).
     */
    protected function prepareForValidation(): void
    {
        $cartItemsRaw = $this->input('cart_items');
        $parsedItems = [];

        if (!empty($cartItemsRaw)) {
            if (is_string($cartItemsRaw)) {
                $decoded = json_decode($cartItemsRaw, true);
                if (is_array($decoded)) {
                    $parsedItems = $decoded;
                }
            } elseif (is_array($cartItemsRaw)) {
                $parsedItems = $cartItemsRaw;
            }
        }

        $metode = strip_tags(trim($this->input('metode_pembayaran', 'COD')));
        // Proteksi: Larang metode QR / QRIS sesuai spesifikasi
        if (empty($metode) || stripos($metode, 'QR') !== false) {
            $metode = 'COD';
        }

        $this->merge([
            'parsed_items'      => $parsedItems,
            'metode_pembayaran' => $metode,
            'nama_pemesan'      => strip_tags(trim($this->input('nama_pemesan', ''))),
            'alamat_pengiriman' => strip_tags(trim($this->input('alamat_pengiriman', ''))),
            'nomor_telepon'     => preg_replace('/[^0-9+]/', '', trim($this->input('nomor_telepon', ''))),
            'catatan'           => strip_tags(trim($this->input('catatan', ''))),
        ]);
    }

    /**
     * Aturan validasi ketat untuk transaksi pembelian buah (mendukung satuan & multi-item keranjang).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $hasParsedItems = !empty($this->input('parsed_items'));

        return [
            'cart_items'                => ['nullable'],
            'parsed_items'              => ['nullable', 'array'],
            'parsed_items.*.produk_id'  => ['required_with:parsed_items', 'exists:produks,id'],
            'parsed_items.*.jumlah'     => ['required_with:parsed_items', 'integer', 'min:1', 'max:1000'],

            'produk_id'         => [$hasParsedItems ? 'nullable' : 'required', 'exists:produks,id'],
            'jumlah'            => [$hasParsedItems ? 'nullable' : 'required', 'integer', 'min:1', 'max:1000'],

            'metode_pembayaran' => ['nullable', 'string', 'max:50'],
            'nama_pemesan'      => ['nullable', 'string', 'max:100'],
            'nomor_telepon'     => ['required', 'string', 'max:25'],
            'alamat_pengiriman' => ['required', 'string', 'max:500'],
            'catatan'           => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Pesan validasi kustom.
     */
    public function messages(): array
    {
        return [
            'produk_id.required'         => 'Produk yang dipilih tidak valid.',
            'produk_id.exists'           => 'Produk buah tidak ditemukan dalam database.',
            'jumlah.required'            => 'Jumlah pembelian buah wajib diisi.',
            'jumlah.integer'             => 'Jumlah pembelian harus berupa angka bulat.',
            'jumlah.min'                 => 'Jumlah pembelian minimal 1 kg/buah.',
            'nomor_telepon.required'     => 'Nomor telepon penerima wajib diisi.',
            'nomor_telepon.regex'        => 'Format nomor telepon tidak valid (hanya angka dan tanda +).',
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
        ];
    }
}

