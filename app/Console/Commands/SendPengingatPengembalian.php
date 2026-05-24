<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendPengingatPengembalian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:pengingat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat H-1 pengembalian alat PLN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $besok = \Carbon\Carbon::tomorrow()->toDateString();
        
        $peminjamanList = \App\Models\Peminjaman::with('user')
            ->where('status_peminjaman', 'Sedang Dipinjam')
            ->whereDate('estimasi_kembali', $besok)
            ->get();

        $count = 0;
        foreach ($peminjamanList as $peminjaman) {
            if ($peminjaman->user && $peminjaman->user->email) {
                try {
                    $emailAdmin = env('MAIL_FROM_ADDRESS', config('mail.from.address'));
                    \Illuminate\Support\Facades\Mail::to($peminjaman->user->email)
                        ->cc($emailAdmin)
                        ->send(new \App\Mail\NotifikasiPeminjaman($peminjaman, 'pengingat'));
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$peminjaman->user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("Berhasil mengirim {$count} email pengingat H-1.");
    }
}
