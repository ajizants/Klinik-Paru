<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AutoLogoutIfSessionExpired
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && ! $request->hasSession()) {
                                                        // Jika user login tapi session habis
            return redirect()->route('logout.session'); // arahkan ke logout khusus session timeout
        }

        return $next($request);
    }
}
