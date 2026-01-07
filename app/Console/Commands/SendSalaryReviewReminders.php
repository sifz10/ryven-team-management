<?php

namespace App\Console\Commands;

use App\Models\SalaryReview;
use App\Models\User;
use App\Notifications\SalaryReviewReminder;
use Illuminate\Console\Command;

class SendSalaryReviewReminders extends Command
{
    protected $signature = 'salary-reviews:send-reminders';

    protected $description = 'Send daily reminders for salary reviews due within 5 days';

    public function handle(): int
    {
        // Get all salary reviews that are pending and due within 5 days
        $dueReviews = SalaryReview::where('status', 'pending')
            ->whereDate('review_date', '>=', now()->toDateString())
            ->whereDate('review_date', '<=', now()->addDays(5)->toDateString())
            ->with('employee')
            ->get();

        if ($dueReviews->isEmpty()) {
            $this->info('No salary reviews due for reminders.');
            return 0;
        }

        // Get admin user (assuming user with ID 1 or role admin)
        $adminUsers = User::role('admin')->get(); // If using Spatie roles
        if ($adminUsers->isEmpty()) {
            // Fallback to first user if no admin role
            $adminUsers = User::limit(1)->get();
        }

        $count = 0;
        foreach ($dueReviews as $review) {
            foreach ($adminUsers as $admin) {
                $admin->notify(new SalaryReviewReminder($review));
                $count++;
            }
        }

        $this->info("Sent {$count} salary review reminders.");
        return 0;
    }
}
