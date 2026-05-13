<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Mail\OverdueReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckOverduePeminjaman extends Command
{
    // Nama command yang akan dipanggil nanti
    protected $signature = 'logistik:check-overdue';

    // Deskripsi command
    protected $description = 'Cek alat yang melewati batas waktu dan kirim email peringatan';

    public function handle()
    {
        // Cari alat yang statusnya 'Sedang Dipinjam' dan waktu sekarang sudah lewat dari estimasi_kembali
        $overdues = Peminjaman::with('user')
            ->where('status_peminjaman', 'Sedang Dipinjam')
            ->where('estimasi_kembali', '<', Carbon::now())
            ->get();

        $count = 0;

        foreach ($overdues as $pinjam) {
            // Pastikan user memiliki email
            if ($pinjam->user && $pinjam->user->email) {
                Mail::to($pinjam->user->email)->send(new OverdueReminderMail($pinjam));
                $count++;
            }
        }

        $this->info("Berhasil mengirim {$count} email peringatan overdue.");
    }
}