<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // Optional: Register additional route groups
        Route::middleware('api')
            ->prefix('mobile')
            ->group(base_path('routes/api.php'));
    }
    
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()->id ?? $request->ip());
        });

        RateLimiter::for('loginCheck', function (Request $request) {
            return Limit::perMinute(5)->by('login:' . $request->ip());
        });
    }
}
