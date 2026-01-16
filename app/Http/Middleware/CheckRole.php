<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah sudah login
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('pegawai.login');
        }

        $user = Auth::guard('employee')->user();

        // 2. Cek apakah user punya salah satu role yang diizinkan
        // Kita gunakan method hasRole dari Spatie (karena kamu punya tabel roles)
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // 3. Jika tidak punya role yang sesuai, lempar ke halaman 403 (Forbidden)
        // Atau redirect balik ke dashboard mereka sendiri
        return redirect()->route('pegawai.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman Admin!');
    }
}