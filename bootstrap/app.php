<?php
// Register a Global Helper Function
// Option A: Add helper to app/helpers.php
// Register your helper functions BEFORE the Application is configured
// require_once __DIR__ . '/../app/Helpers/helper.php';

// Option B: Autoload via composer.json
// Recommended, because :Helpers are loaded automatically on every request or Artisan command
// After editing composer.json, run :composer dump-autoload
// "autoload": {
//     "files": [
//         "app/helpers.php"
//     ]
// }


use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

// you may use the withSchedule method in your application's bootstrap/app.php file to define your scheduled tasks.
// like this : ->withSchedule(function (Schedule $schedule) {
use Illuminate\Console\Scheduling\Schedule;
use App\CustomTasks\DeleteUsers;

// running commands from bootstrap/app.php
use App\Console\Commands\HelloCommand;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Adding API routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        // How to Register Your Own Service Providers
        // This is now the recommended place to register custom service providers
        App\Providers\RoutingServiceProvider::class,

        // adding this will include all manualy created services
        App\Providers\AppServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,

            // Append custom middleware (also to web group)
            // Appends your own custom middleware to the default web stack.
            // For logging, localization, or any cross-cutting concerns applied to all web routes.
            // \App\Http\Middleware\CustomMiddleware::class,
        ]);
            
        // Register a named (alias) middleware
        // Allows you to use 'custom' as a middleware name in routes.
        // Useful for applying middleware selectively to routes
        // $middleware->alias([
        //     'custom' => \App\Http\Middleware\CustomMiddleware::class,
        // ]);

        // Register a custom group of middleware
        // Defines a named group of middleware.
        // When you want to reuse multiple middleware together
        // $middleware->group([
        //     'custom' => [
        //         \App\Http\Middleware\CustomMiddleware::class,
        //         \App\Http\Middleware\CustomMiddleware2::class,
        //     ],
        // ]);
    })
    ->withCommands([
        // If using lightweight command definition via routes/console.php
        // or like this
        HelloCommand::class,
    ])
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(new DeleteUsers)->everyMinute();

        // if you need to run a command in scheduler
        $schedule->command('app:hello-command')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // old style for app/Exceptions/Handler.php
        // $exceptions->use(App\Exceptions\Handler::class);

        // new way
        // 1. Register custom renderable exception response
        // $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'message' => 'Validation failed',
        //             'errors' => $e->errors(),
        //         ], 422);
        //     }
        // });

        // 2. Register reportable logic
        // $exceptions->report(function (\Throwable $e) {
        //     if ($e instanceof \App\Exceptions\CustomAlertException) {
        //         // Log or send alert
        //     }
        // });

        // 3. Ignore specific exceptions from being reported
        // $exceptions->dontReport([
        //     \App\Exceptions\NonCriticalException::class,
        // ]);
    })->create();
