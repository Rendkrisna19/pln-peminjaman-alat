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
        Schema::create('tbl_unit_lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit'); // Contoh: PLTA ASAHAN 3, PLTMH BATANG GADIS
            $table->string('jenis_unit')->nullable(); // Contoh: PLTA, PLTMH, Internal, dll. (Dibuat nullable jika sewaktu-waktu belum diketahui jenisnya)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_lokasis');
    }
};
