<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user yang login ada di dalam daftar role yang diizinkan di route
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}