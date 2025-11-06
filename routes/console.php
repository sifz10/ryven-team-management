<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule note reminders to check every minute
Schedule::command('notes:send-reminders')->everyMinute();

// Process scheduled social media posts every minute
Schedule::command('social:process-scheduled')->everyMinute();

// Fetch emails from all active accounts every minute for near-real-time sync
Schedule::job(new \App\Jobs\FetchEmails())->everyMinute();
