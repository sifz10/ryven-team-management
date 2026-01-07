<?php

namespace App\Notifications;

use App\Models\SalaryReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SalaryReviewReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SalaryReview $salaryReview,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->salaryReview->employee;
        $daysUntilReview = $this->salaryReview->review_date->diffInDays(now());

        return (new MailMessage)
            ->subject("⏰ Salary Review Reminder - {$employee->first_name} {$employee->last_name}")
            ->greeting("Hello,")
            ->line("This is a reminder to review the salary of **{$employee->first_name} {$employee->last_name}** (Position: {$employee->position}).")
            ->line("**Review Details:**")
            ->line("- **Hired Date:** " . $employee->hired_at->format('M d, Y'))
            ->line("- **6-Month Review Due:** " . $this->salaryReview->review_date->format('M d, Y'))
            ->line("- **Days Remaining:** {$daysUntilReview} day(s)")
            ->line("- **Current Salary:** " . number_format($employee->salary, 2) . " " . ($employee->currency ?? 'USD'))
            ->action('Review Salary', route('salary-reviews.show', $this->salaryReview->id))
            ->line("Please evaluate their performance and adjust the salary if needed.");
    }

    public function toArray(object $notifiable): array
    {
        $employee = $this->salaryReview->employee;

        return [
            'type' => 'salary_review_reminder',
            'title' => "Salary Review Reminder",
            'message' => "Review {$employee->first_name} {$employee->last_name}'s salary (due {$this->salaryReview->review_date->format('M d, Y')})",
            'salary_review_id' => $this->salaryReview->id,
            'employee_id' => $employee->id,
            'url' => route('salary-reviews.show', $this->salaryReview->id),
        ];
    }
}
