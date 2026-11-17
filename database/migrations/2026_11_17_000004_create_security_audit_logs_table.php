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
        if (!Schema::hasTable('security_audit_logs')) {
            Schema::create('security_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event_type', 50)->nullable(); // LOGIN_FAILED, LOGIN_SUCCESS, REGISTER, TRANSACTION_CREATED, dll.
                $table->string('description', 255)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->json('details')->nullable(); // Data non-sensitif pendukung
                $table->timestamps();
            });
        } else {
            Schema::table('security_audit_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('security_audit_logs', 'event_type')) {
                    $table->string('event_type', 50)->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('security_audit_logs', 'description')) {
                    $table->string('description', 255)->nullable()->after('event_type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audit_logs');
    }
};

