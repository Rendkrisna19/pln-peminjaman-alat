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
        Schema::create('tbl_rak_penyimpanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rak'); // Contoh: RAK C2
            $table->string('lokasi_rak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rakpenyimpanans');
    }
};
