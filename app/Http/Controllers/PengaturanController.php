<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function edit()
    {
        $pengaturan = Pengaturan::firstOrCreate([
            'id' => 1
        ], [
            'nama_toko' => 'Toko Buah Luragung',
            'whatsapp' => '6285861403683',
            'alamat' => 'Jl. Raya Luragung, Kuningan',
            'jam_operasional' => '08:00 - 21:00 WIB'
        ]);

        if (in_array($pengaturan->whatsapp, ['6281234567890', '6285798089458'])) {
            $pengaturan->update(['whatsapp' => '6285861403683']);
        }

        // Diperbarui menjadi 'admin.pengaturan' sesuai lokasi file view di folder admin/
        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_toko'       => 'required|string|max:255',
            'whatsapp'        => 'required|string|max:20|regex:/^[0-9+]+$/',
            'alamat'          => 'nullable|string|max:500',
            'jam_operasional' => 'nullable|string|max:100',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'whatsapp.required'  => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex'     => 'Format nomor WhatsApp tidak valid.',
        ]);

        $pengaturan = Pengaturan::firstOrCreate(['id' => 1]);

        // Mencegah Mass Assignment & Stored XSS dengan memperbarui hanya data yang divalidasi dan disanitasi
        $pengaturan->update([
            'nama_toko'       => strip_tags($validated['nama_toko']),
            'whatsapp'        => preg_replace('/[^0-9+]/', '', $validated['whatsapp']),
            'alamat'          => strip_tags($validated['alamat'] ?? ''),
            'jam_operasional' => strip_tags($validated['jam_operasional'] ?? ''),
        ]);

        return redirect()->route('pengaturan.edit')->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}