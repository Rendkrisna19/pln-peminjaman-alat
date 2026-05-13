⏰ PANDUAN SETUP NOTIFIKASI EMAIL OTOMATIS (CRON JOB) DI SHARED HOSTING
Dokumen ini adalah panduan pengingat untuk mengaktifkan sistem Email Pengingat Overdue (Telat Mengembalikan Alat) saat aplikasi di-upload ke Shared Hosting (cPanel).

Sistem ini menggunakan fitur Scheduler Laravel yang membutuhkan Cron Job agar bisa berjalan otomatis setiap hari tanpa perlu membuka halaman web.

TAHAP 1: Persiapan File .env
Sebelum mengatur Cron Job, pastikan konfigurasi email di file .env di hosting sudah benar menggunakan Sandi Aplikasi (App Password) Gmail.

Cuplikan kode
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email.logistik.anda@gmail.com
MAIL_PASSWORD=sandiaplikasi16karaktertanpaspasi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="email.logistik.anda@gmail.com"
MAIL_FROM_NAME="Admin Logistik PLN"
(Jangan lupa jalankan php artisan optimize:clear di terminal hosting jika Anda baru saja mengubah file .env).

TAHAP 2: Setup Cron Job di cPanel (Wajib Dilakukan)
Agar Laravel tahu kapan harus mengirim email (jam 08:00 pagi sesuai kode di routes/console.php), server cPanel harus disetting untuk mengecek aplikasi setiap menit.

Login ke cPanel hosting Anda.

Cari kotak pencarian dan ketik "Cron Jobs", lalu klik menu tersebut (biasanya ikon roda gigi atau jam).

Gulir ke bawah ke bagian Add New Cron Job.

Pada dropdown Common Settings, pilih:
👉 Once Per Minute (* * * * *) atau "Sekali Setiap Menit".

(Catatan: Jangan khawatir email akan terkirim setiap menit. Ini hanya agar cPanel mengetuk pintu Laravel setiap menit, tapi Laravel hanya akan mengirim email saat jam 08:00 pagi).

Pada kolom Command, masukkan baris perintah berikut:

Bash
cd /home/username_cpanel/public_html/folder_laravel_anda && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
⚠️ PENTING - YANG HARUS ANDA UBAH PADA COMMAND DI ATAS:

username_cpanel ➔ Ganti dengan username cPanel Anda.

folder_laravel_anda ➔ Ganti dengan nama folder tempat Anda menaruh file project Laravel.

/usr/local/bin/php ➔ Ini adalah lokasi compiler PHP. Jika command di atas gagal/tidak jalan, ubah bagian ini cukup menjadi php saja, atau tanyakan ke Live Chat/Tiket Support hosting: "Permisi, berapa path PHP untuk menjalankan cron job di hosting ini?"

Klik tombol Add New Cron Job.

TAHAP 3: Cara Mengetes Apakah Cron Job Sudah Berjalan
Jika Anda ingin mengetes apakah email benar-benar masuk sebelum menunggu jam 8 pagi besoknya:

Buka database Anda (phpMyAdmin), cari tabel tbl_peminjaman.

Ubah kolom estimasi_kembali pada salah satu data yang berstatus "Sedang Dipinjam" menjadi tanggal kemarin (agar dianggap telat).

Buka file routes/console.php di project Anda.

Ubah sementara jadwalnya dari:
Schedule::command('logistik:check-overdue')->dailyAt('08:00');
Menjadi:
Schedule::command('logistik:check-overdue')->everyMinute();

Tunggu 1-2 menit, lalu cek kotak masuk Gmail (atau folder Spam) dari teknisi yang telat tersebut.

Jika email berhasil masuk, KEMBALIKAN LAGI kodenya menjadi ->dailyAt('08:00'); agar teknisi tidak di-spam email setiap menit!