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
        Schema::create('tbl_item_inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peralatan_id')->constrained('tbl_peralatan');
            $table->string('kode_barcode')->unique();
            $table->enum('kondisi', ['Baik', 'Rusak'])->default('Baik');
            $table->enum('status_ketersediaan', ['Tersedia', 'Dipinjam', 'Diperbaiki'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_inventaris');
    }
};
