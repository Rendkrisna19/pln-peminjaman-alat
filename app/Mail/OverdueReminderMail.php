<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OverdueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PENGINGAT: Batas Waktu Pengembalian Alat Telah Habis - ' . $this->peminjaman->kode_peminjaman,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.overdue_reminder', // Kita akan buat view ini di bawah
        );
    }
}