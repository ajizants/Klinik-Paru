<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetRealIpFromCloudflare
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has('CF-Connecting-IP')) {
            $request->server->set('REMOTE_ADDR', $request->header('CF-Connecting-IP'));
        }

        return $next($request);
    }
}
