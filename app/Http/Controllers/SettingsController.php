<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Menampilkan Halaman Pengaturan (Dinamis sesuai Role)
     */
   public function index()
    {
        $user = Auth::user();
        // UBAH BARIS INI: Langsung panggil 'settings.index' tanpa $user->role
        return view('settings.index', compact('user')); 
    }

    /**
     * Memproses Semua Pembaruan (Profil, Foto, dan Password)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255', // Di view Anda menggunakan name="name"
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tbl_users', 'email')->ignore($user->id),
            ],
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'password' => 'nullable|string|min:8|confirmed', // Nullable karena opsional
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'foto_profil.image' => 'File harus berupa gambar (JPG/PNG).',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // 2. Update Data Profil Teks
        $user->nama_lengkap = $request->name; // Sesuaikan dengan kolom database Anda
        $user->email = $request->email;

        // 3. Update Foto Profil (Jika ada file yang diupload)
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            // Simpan foto baru ke folder 'avatars'
            $path = $request->file('foto_profil')->store('avatars', 'public');
            $user->foto_profil = $path;
        }

        // 4. Update Password (Hanya jika diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan semua perubahan ke database
        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui!');
    }
}