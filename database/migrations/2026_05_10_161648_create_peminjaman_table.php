<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti namanya jadi tbl_peminjaman
        Schema::create('tbl_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('user_id')->constrained('tbl_users')->cascadeOnDelete();
            $table->foreignId('unit_tujuan_id')->constrained('tbl_unit_lokasi')->cascadeOnDelete();
            $table->dateTime('tanggal_pengajuan');
            $table->dateTime('estimasi_kembali');
            $table->dateTime('tanggal_dikembalikan')->nullable();
            $table->text('keterangan_pekerjaan');
            $table->enum('status_peminjaman', ['Menunggu Verifikasi', 'Disetujui', 'Sedang Dipinjam', 'Dikembalikan', 'Ditolak'])->default('Menunggu Verifikasi');
            $table->foreignId('admin_id')->nullable()->constrained('tbl_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_peminjaman');
    }
};