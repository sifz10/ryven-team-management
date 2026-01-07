<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\SalaryAdjustmentHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalaryAdjustmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Employee $employee,
        public SalaryAdjustmentHistory $adjustment,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $typeLabels = [
            'promotion' => '⬆️ Promotion',
            'demotion' => '⬇️ Demotion',
            'adjustment' => '↔️ Salary Adjustment',
            'manual' => '📝 Manual Adjustment',
            'bonus' => '🎁 Bonus',
        ];

        $typeLabel = $typeLabels[$this->adjustment->type] ?? 'Salary Update';

        return new Envelope(
            subject: "Salary Updated - {$typeLabel} for {$this->employee->first_name} {$this->employee->last_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salary-adjustment',
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
