<?php

namespace App\Mail;

use App\Models\ProjectTicket;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectTicket $ticket;
    public string $notificationType;
    public string $message;
    public ?Employee $triggeredBy;
    public ?string $commentText;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjectTicket $ticket, string $notificationType, string $message, ?Employee $triggeredBy = null, ?string $commentText = null)
    {
        $this->ticket = $ticket;
        $this->notificationType = $notificationType;
        $this->message = $message;
        $this->triggeredBy = $triggeredBy;
        $this->commentText = $commentText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->notificationType) {
            'created' => "New Ticket Created: {$this->ticket->ticket_number}",
            'replied' => "New Reply on Ticket: {$this->ticket->ticket_number}",
            'status_changed' => "Ticket Status Changed: {$this->ticket->ticket_number}",
            'mentioned' => "You were mentioned in Ticket: {$this->ticket->ticket_number}",
            'assigned' => "You were assigned to Ticket: {$this->ticket->ticket_number}",
            default => "Ticket Notification: {$this->ticket->ticket_number}",
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-notification',
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
