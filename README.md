# Toko Buah Segar Luragung

Aplikasi berbasis web untuk manajemen inventaris dan transaksi penjualan pada **Toko Buah Segar Luragung**. Dibangun menggunakan framework Laravel untuk memenuhi Tugas Besar Mata Kuliah Cyber Security.
URL: https://diktanuranikiranaa.my.id/

---

## 🚀 Fitur Utama
1. **Autentikasi & Otorisasi:** Sistem registrasi dan login user dengan manajemen peran (*Role-Based Access Control* untuk Admin dan User).
2. **Pengelolaan Data (CRUD):** Manajemen data buah, kategori, stok, dan transaksi yang terhubung langsung ke database MySQL.
3. **Laporan & Rekap:** Fitur rekapitulasi data penjualan harian/bulanan.

---

## 🛠️ Teknologi & Stack
* **Backend:** PHP (Laravel Framework)
* **Database:** MySQL / MariaDB
* **Frontend:** Blade Templating, Bootstrap / Tailwind CSS
* **Deployment:** Online / Public Subdomain dengan HTTPS aktif
* **Keamanan:** Bcrypt Password Hashing, CSRF Protection, Input Validation & Sanitization, Session Security.

---

## 🔒 Penerapan Aspek Cyber Security
* **Password Hashing:** Kata sandi pengguna disimpan menggunakan algoritma *Bcrypt* yang aman di database, bukan *plaintext*[cite: 12].
* **Pencegahan SQL Injection & XSS:** Menggunakan fungsi *Eloquent ORM* bawaan Laravel serta validasi input yang ketat untuk mencegah injeksi berbahaya[cite: 12].
* **Manajemen Sesi & Token:** Sesi pengguna diamankan menggunakan enkripsi framework dengan cookie yang aman[cite: 12].
* **Environment Variables:** Konfigurasi rahasia (seperti kredensial database dan *App Key*) disimpan di dalam file `.env` dan tidak di-commit ke repository publik[cite: 12].

---

## ⚙️ Cara Instalasi & Menjalankan Secara Lokal
1. **Clone repository ini:**
   ```bash
   git clone [https://github.com/diktanuranikirana516-dev/Toko_Buah_Segar_Luragung.git](https://github.com/diktanuranikirana516-dev/Toko_Buah_Segar_Luragung.git)
