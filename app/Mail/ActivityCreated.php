<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivityCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Employee $employee,
        public EmployeePayment $activity,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $activityTypes = [
            'achievement' => '🟢 Achievement',
            'warning' => '🔴 Warning',
            'payment' => '🔵 Payment',
            'note' => '⚪ Note',
        ];

        $typeLabel = $activityTypes[$this->activity->activity_type] ?? 'Activity';

        return new Envelope(
            subject: "New {$typeLabel} - {$this->employee->first_name} {$this->employee->last_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.activity-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
