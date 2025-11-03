<?php

namespace App\Mail;

use App\Models\UatProject;
use App\Models\UatUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UatInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $uatUser;
    public $invitedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(UatProject $project, UatUser $uatUser, UatUser $invitedBy)
    {
        $this->project = $project;
        $this->uatUser = $uatUser;
        $this->invitedBy = $invitedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve been invited to UAT: ' . $this->project->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.uat-invitation',
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

