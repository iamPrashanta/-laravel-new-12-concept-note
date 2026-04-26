<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// to use manualy created Services
// Register in a Service Provider
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // add AppServiceProvider to bootstrap/app.php in here withProviders
        $this->app->singleton(PaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
