<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// PERBAIKAN: implements ShouldQueue DIHAPUS agar email langsung terkirim!
class NotifikasiPeminjaman extends Mailable 
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $jenisNotif;

    public function __construct(Peminjaman $peminjaman, $jenisNotif)
    {
        $this->peminjaman = $peminjaman;
        $this->jenisNotif = $jenisNotif; // 'baru', 'disetujui', 'ditolak', 'dikembalikan'
    }

    public function envelope(): Envelope
    {
        $subject = 'Notifikasi E-Tools PLN';

        if ($this->jenisNotif == 'baru') {
            $subject = 'PERMOHONAN BARU: ' . $this->peminjaman->kode_peminjaman;
        } elseif ($this->jenisNotif == 'dikembalikan') {
            $subject = 'ALAT DIKEMBALIKAN: ' . $this->peminjaman->kode_peminjaman;
        } else {
            $subject = 'UPDATE STATUS: ' . $this->peminjaman->kode_peminjaman;
        }

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi_peminjaman',
        );
    }
}