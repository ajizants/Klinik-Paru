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
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

}
