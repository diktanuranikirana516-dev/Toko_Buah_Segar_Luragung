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
        if (Schema::hasTable('transaksi') && !Schema::hasColumn('transaksi', 'metode_pembayaran')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->string('metode_pembayaran', 50)->default('COD')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaksi') && Schema::hasColumn('transaksi', 'metode_pembayaran')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->dropColumn('metode_pembayaran');
            });
        }
    }
};
