<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Tambahkan file routes yang baru
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/igd.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/kominfo.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/pendaftaran.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/roLab.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/kasir.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/tu.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/part/ranap.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
