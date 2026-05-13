<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        // Jika user sudah login, langsung arahkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBerdasarkanRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // 2. Cek Eksistensi Email (Validasi Spesifik Email)
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Maaf, email ini tidak terdaftar di sistem kami.',
            ])->onlyInput('email');
        }

        // 3. Cek Kecocokan Password (Validasi Spesifik Password)
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah!',
            ])->onlyInput('email');
        }

        // 4. Set Konfigurasi Cookies 7 Hari (jika dicentang)
        $remember = $request->has('remember');
        if ($remember) {
            // 7 Hari = 10080 Menit
            config(['session.lifetime' => 10080]);
        } else {
            // Default session habis saat browser ditutup
            config(['session.expire_on_close' => true]);
        }

        // 5. Proses Login Menggunakan Auth Facade
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // 6. Simpan Histori Login
        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        // 7. Arahkan ke Dashboard sesuai Role
        return $this->redirectBerdasarkanRole($user->role);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }

    // Fungsi helper untuk merapikan arah redirect
    private function redirectBerdasarkanRole($role)
    {
        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($role === 'supervisor') {
            return redirect()->intended(route('supervisor.dashboard'));
        } else {
            return redirect()->intended(route('pegawai.dashboard'));
        }
    }
}
