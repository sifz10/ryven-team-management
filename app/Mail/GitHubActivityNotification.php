<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\GitHubLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GitHubActivityNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public GitHubLog $activity,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $eventLabels = [
            'push' => '📤 Push',
            'pull_request' => '🔀 Pull Request',
            'pull_request_review' => '🔍 PR Review',
            'issues' => '📋 Issue',
            'issue_comment' => '💬 Issue Comment',
            'create' => '✨ Create',
            'delete' => '🗑️ Delete',
        ];

        $eventLabel = $eventLabels[$this->activity->event_type] ?? 'Activity';

        return new Envelope(
            subject: "$eventLabel - {$this->activity->repository_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.github-activity',
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
