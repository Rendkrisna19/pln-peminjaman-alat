<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PASTIKAN NAMANYA tbl_login_logs (tambahkan tbl_)
        Schema::create('tbl_login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_users')->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->dateTime('login_at'); // Menggunakan dateTime seperti yang kita bahas sebelumnya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // PASTIKAN NAMANYA tbl_login_logs JUGA DI SINI
        Schema::dropIfExists('tbl_login_logs');
    }
};