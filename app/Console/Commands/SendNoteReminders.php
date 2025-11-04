<?php

namespace App\Console\Commands;

use App\Mail\NoteReminderMail;
use App\Models\PersonalNote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNoteReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for personal notes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notes = PersonalNote::pendingReminders()
            ->with(['recipients', 'user'])
            ->get();

        if ($notes->isEmpty()) {
            $this->info('No pending reminders found.');
            return 0;
        }

        foreach ($notes as $note) {
            // Get all recipient emails
            $emails = $note->recipients->pluck('email')->toArray();
            
            if (empty($emails)) {
                $this->warn("Note #{$note->id} has no recipients. Skipping.");
                continue;
            }

            try {
                // Send to all recipients
                foreach ($emails as $email) {
                    Mail::to($email)->send(new NoteReminderMail($note));
                }

                // Mark as sent
                $note->update(['reminder_sent' => true]);
                
                $this->info("Reminder sent for note #{$note->id}: {$note->title}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for note #{$note->id}: {$e->getMessage()}");
            }
        }

        $this->info("Processed {$notes->count()} reminder(s).");
        return 0;
    }
}
