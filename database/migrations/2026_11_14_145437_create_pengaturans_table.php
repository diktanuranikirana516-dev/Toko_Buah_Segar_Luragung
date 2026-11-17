<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->default('Toko Buah Luragung');
            $table->string('whatsapp')->default('6285861403683');
            $table->text('alamat')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
