<?php

namespace App\Mail;

use App\Models\ClientTeamMember;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientTeamMemberInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $teamMember;
    public $client;
    public $password;
    public $acceptUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(ClientTeamMember $teamMember, Client $client, string $password)
    {
        $this->teamMember = $teamMember;
        $this->client = $client;
        $this->password = $password;
        $this->acceptUrl = route('client.team.accept', ['token' => $teamMember->invitation_token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Team Invitation - ' . $this->client->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client-team-invitation',
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
