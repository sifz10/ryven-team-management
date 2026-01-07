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

        // Configure authentication redirects for different guards
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('client') || $request->is('client/*')) {
                return route('client.login');
            }
            if ($request->is('employee') || $request->is('employee/*')) {
                return route('employee.login');
            }
            return route('login'); // Default to client login
        });

        // Configure where to redirect authenticated users trying to access guest routes
        $middleware->redirectUsersTo(function ($request) {
            // Check which guard is authenticated
            if (auth()->guard('client')->check()) {
                return route('client.dashboard');
            }
            if (auth()->guard('employee')->check()) {
                return route('employee.dashboard');
            }
            if (auth()->guard('web')->check()) {
                return route('dashboard');
            }
            return route('client.dashboard'); // Default to client dashboard
        });
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Generate daily checklists every day at midnight
        $schedule->command('checklists:generate-daily')->daily();
        
        // Send salary review reminders daily for reviews due within 5 days
        $schedule->command('salary-reviews:send-reminders')->daily()->at('09:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
