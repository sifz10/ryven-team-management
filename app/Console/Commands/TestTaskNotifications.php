<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;
use App\Models\ProjectTask;

class TestTaskNotifications extends Command
{
    protected $signature = 'test:task-notifications';
    protected $description = 'Test task notification system';

    public function handle()
    {
        $this->info('Testing Task Notification System');
        $this->info('================================');
        $this->newLine();

        // Check users
        $totalUsers = User::count();
        $usersWithEmployees = User::whereHas('employee')->count();

        $this->info("Total Users: {$totalUsers}");
        $this->info("Users with Employee Records: {$usersWithEmployees}");
        $this->newLine();

        // List users with employees
        $this->info('Users with Employee Records:');
        User::whereHas('employee')->with('employee')->get()->each(function ($user) {
            $this->line("  - {$user->name} ({$user->email}) - Employee ID: {$user->employee->id}");
        });
        $this->newLine();

        // Check recent task
        $recentTask = ProjectTask::latest()->first();
        if ($recentTask) {
            $this->info("Most Recent Task:");
            $this->line("  ID: {$recentTask->id}");
            $this->line("  Title: {$recentTask->title}");
            $this->line("  Status: {$recentTask->status}");
            $this->line("  Assigned To: " . ($recentTask->assigned_to ?? 'None'));
            $this->newLine();

            // Check who would receive notifications for this task
            $recipients = collect();

            if ($recentTask->assigned_to) {
                $assignedEmployee = Employee::find($recentTask->assigned_to);
                if ($assignedEmployee && $assignedEmployee->user) {
                    $recipients->push($assignedEmployee->user);
                    $this->line("  ✓ Assigned user would receive notification: {$assignedEmployee->user->email}");
                }
            }

            $adminUsers = User::whereHas('employee')->get();
            $recipients = $recipients->merge($adminUsers)->unique('id');

            $this->info("Total Recipients: " . $recipients->count());
            $recipients->each(function ($user) {
                $this->line("  → {$user->name} ({$user->email})");
            });
        } else {
            $this->warn('No tasks found in database');
        }

        $this->newLine();
        $this->info('Test complete!');

        return 0;
    }
}
