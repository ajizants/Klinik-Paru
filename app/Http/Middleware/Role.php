<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     if ($request->user()->role === 'admin') {
    //         return $next($request);
    //     }

    //     if ($request->user()->role == $role) {
    //         return $next($request);
    //     }

    //     // abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    //     return redirect()->route('forbidden');
    // }
    public function handle($request, Closure $next, ...$roles)
    {
        // Periksa apakah pengguna adalah admin
        if (auth()->user()->role === 'admin') {
            // Jika admin, langsung lanjutkan ke permintaan berikutnya
            return $next($request);
        }

        // Periksa apakah pengguna memiliki salah satu peran yang diizinkan
        if (!in_array(auth()->user()->role, $roles)) {
            // Jika tidak, arahkan ke halaman larangan akses
            $previousUrl = url()->previous();
            session()->flash('previous_url', $previousUrl);
            return redirect()->route('forbidden')->with('previous_url', $previousUrl);
        }

        // Jika peran sesuai, lanjutkan permintaan
        return $next($request);
    }

}
