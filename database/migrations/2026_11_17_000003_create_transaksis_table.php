<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->string('kode_transaksi')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->foreignId('produk_id')->nullable()->constrained('produks')->cascadeOnDelete();
                $table->integer('jumlah')->default(1);
                $table->decimal('harga_satuan', 12, 2)->default(0);
                $table->decimal('total_harga', 12, 2)->default(0);
                $table->string('status', 30)->default('diproses'); // selesai / diproses / menunggu / dibatalkan
                $table->string('metode_pembayaran', 50)->default('COD'); // COD, Transfer BCA, Transfer Mandiri, GoPay, OVO (Tanpa QR)
                $table->string('alamat_pengiriman', 500)->nullable();
                $table->string('nomor_telepon', 25)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('transaksi', function (Blueprint $table) {
                if (!Schema::hasColumn('transaksi', 'kode_transaksi')) {
                    $table->string('kode_transaksi')->nullable()->after('id');
                }
                if (!Schema::hasColumn('transaksi', 'harga_satuan')) {
                    $table->decimal('harga_satuan', 12, 2)->default(0)->after('jumlah');
                }
                if (!Schema::hasColumn('transaksi', 'metode_pembayaran')) {
                    $table->string('metode_pembayaran', 50)->default('COD')->after('status');
                }
                if (!Schema::hasColumn('transaksi', 'alamat_pengiriman')) {
                    $table->string('alamat_pengiriman', 500)->nullable()->after('status');
                }
                if (!Schema::hasColumn('transaksi', 'nomor_telepon')) {
                    $table->string('nomor_telepon', 25)->nullable()->after('alamat_pengiriman');
                }
                if (!Schema::hasColumn('transaksi', 'catatan')) {
                    $table->text('catatan')->nullable()->after('nomor_telepon');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
