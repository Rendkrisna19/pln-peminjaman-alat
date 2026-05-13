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
      Schema::create('tbl_peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->text('spesifikasi')->nullable(); // Dibuat nullable karena tidak semua alat punya spesifikasi detail
            $table->string('foto')->nullable(); // Untuk menyimpan path gambar alat
            $table->integer('total_stok')->default(0); // Kolom total_stok ditambahkan dengan nilai default 0
            $table->foreignId('rak_id')->nullable()->constrained('tbl_rak_penyimpanan')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peralatans');
    }
};
