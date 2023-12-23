<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class Role
{
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()->role === 'admin') {
            return $next($request);
        }

        if ($request->user()->role == $role) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }
}
