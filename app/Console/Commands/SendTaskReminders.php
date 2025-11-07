<?php

namespace App\Console\Commands;

use App\Models\ProjectTaskReminder;
use App\Mail\TaskReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending task reminders that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending task reminders...');

        // Get all pending reminders that are due
        $pendingReminders = ProjectTaskReminder::pending()->get();

        if ($pendingReminders->isEmpty()) {
            $this->info('No pending reminders found.');
            return 0;
        }

        $this->info("Found {$pendingReminders->count()} pending reminder(s).");

        $sentCount = 0;
        $errorCount = 0;

        foreach ($pendingReminders as $reminder) {
            try {
                // Use stored recipient email if available, otherwise fetch from relationship
                $recipientEmail = $reminder->recipient_email;

                if (!$recipientEmail) {
                    $recipient = $reminder->recipient();
                    if (!$recipient) {
                        $this->error("Recipient not found for reminder ID {$reminder->id}");
                        $errorCount++;
                        continue;
                    }
                    $recipientEmail = $recipient->email ?? null;
                }

                if (!$recipientEmail) {
                    $this->error("No email address for recipient in reminder ID {$reminder->id}");
                    $errorCount++;
                    continue;
                }

                // Send email
                Mail::to($recipientEmail)->send(new TaskReminderMail($reminder));

                // Send in-app notification if recipient is an employee with a user account
                if ($reminder->recipient_type === 'employee') {
                    $employee = \App\Models\Employee::find($reminder->recipient_id);
                    if ($employee && $employee->user) {
                        $employee->user->notify(new \App\Notifications\TaskReminderNotification($reminder));
                    }
                }

                // Mark as sent
                $reminder->update([
                    'is_sent' => true,
                    'sent_at' => now(),
                ]);

                $this->info("✓ Sent reminder ID {$reminder->id} to {$recipientEmail}");
                $sentCount++;

            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder ID {$reminder->id}: " . $e->getMessage());
                Log::error("Failed to send task reminder ID {$reminder->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Completed: {$sentCount} sent, {$errorCount} errors.");
        return 0;
    }
}
