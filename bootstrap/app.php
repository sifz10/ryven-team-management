<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude GitHub webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'webhook/github',
        ]);

        // Register employee authentication middleware
        $middleware->alias([
            'employee.auth' => \App\Http\Middleware\EmployeeAuth::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'client.must.change.password' => \App\Http\Middleware\ClientMustChangePassword::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Generate daily checklists every day at midnight
        $schedule->command('checklists:generate-daily')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
