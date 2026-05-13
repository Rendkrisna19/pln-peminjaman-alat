<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('tbl_peminjaman')->cascadeOnDelete();
            $table->foreignId('item_inventaris_id')->constrained('tbl_item_inventaris')->cascadeOnDelete();
            
            // Menggunakan string agar lebih fleksibel jika ada perubahan kondisi dari "Baik" ke "Rusak" dll
            $table->string('kondisi_saat_dipinjam'); 
            $table->string('kondisi_saat_kembali')->nullable();
            
            $table->text('catatan_kerusakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_detail_peminjaman');
    }
};