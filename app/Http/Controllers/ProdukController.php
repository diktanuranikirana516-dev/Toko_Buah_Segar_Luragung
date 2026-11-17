<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // 1. Tampilkan Daftar Produk Admin
    public function index()
    {
        $produk = Produk::latest()->paginate(10);
        return view('admin.produk.index', compact('produk'));
    }

    // 2. Form Tambah Produk
    public function create()
    {
        return view('admin.produk.create');
    }

    // 3. Simpan Produk Baru ke Database (Dilindungi StoreProdukRequest)
    public function store(StoreProdukRequest $request)
    {
        $validated = $request->validated();

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            // Menjamin sinkronisasi file ke public/storage pada Windows/XAMPP
            if (!is_link(public_path('storage'))) {
                @copy(storage_path('app/public/' . $gambarPath), public_path('storage/' . $gambarPath));
            }
        }

        Produk::create([
            'nama_produk' => $validated['nama_produk'],
            'harga'       => $validated['harga'],
            'stok'        => $validated['stok'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'gambar'      => $gambarPath,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk buah berhasil ditambahkan!');
    }

    // 4. Form Edit Produk
    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    // 5. Update Data Produk (Dilindungi UpdateProdukRequest)
    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        $validated = $request->validated();

        $gambarPath = $produk->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar) {
                $oldClean = preg_replace('#^(public/|storage/)#', '', ltrim($produk->gambar, '/'));
                if (Storage::disk('public')->exists($oldClean)) {
                    Storage::disk('public')->delete($oldClean);
                }
                if (file_exists(public_path('storage/' . $oldClean))) {
                    @unlink(public_path('storage/' . $oldClean));
                }
            }
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            if (!is_link(public_path('storage'))) {
                @copy(storage_path('app/public/' . $gambarPath), public_path('storage/' . $gambarPath));
            }
        }

        $produk->update([
            'nama_produk' => $validated['nama_produk'],
            'harga'       => $validated['harga'],
            'stok'        => $validated['stok'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'gambar'      => $gambarPath,
        ]);

        return redirect()->route('produk.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // 6. Hapus Produk
    public function destroy(Produk $produk)
    {
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}