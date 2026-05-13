<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_tracking_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_inventaris_id')->constrained('tbl_item_inventaris')->cascadeOnDelete();
            
            // Dibuat nullable karena saat alat kembali ke gudang, tidak ada transaksi pinjaman yang aktif
            $table->foreignId('peminjaman_id')->nullable()->constrained('tbl_peminjaman')->cascadeOnDelete();
            
            $table->foreignId('user_id')->nullable()->constrained('tbl_users')->nullOnDelete();
            $table->foreignId('unit_lokasi_id')->nullable()->constrained('tbl_unit_lokasi')->nullOnDelete();
            
            $table->string('aktivitas');
            $table->dateTime('tanggal_waktu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_tracking_log');
    }
};