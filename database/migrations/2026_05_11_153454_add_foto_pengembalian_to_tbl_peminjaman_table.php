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
        Schema::table('tbl_peminjaman', function (Blueprint $table) {
            // Menambahkan kolom foto_pengembalian setelah kolom status_peminjaman
            $table->string('foto_pengembalian')->nullable()->after('status_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_peminjaman', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('foto_pengembalian');
        });
    }
};