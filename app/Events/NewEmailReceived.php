<?php

namespace App\Events;

use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEmailReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emailMessage;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(EmailMessage $emailMessage, int $userId)
    {
        $this->emailMessage = $emailMessage;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'email.new';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->emailMessage->id,
            'subject' => $this->emailMessage->subject,
            'from' => $this->emailMessage->from_email,
            'from_name' => $this->emailMessage->from_name,
            'preview' => $this->emailMessage->text_body ? substr(strip_tags($this->emailMessage->text_body), 0, 100) : '',
            'received_at' => $this->emailMessage->received_at->toIso8601String(),
            'is_read' => $this->emailMessage->is_read,
            'is_starred' => $this->emailMessage->is_starred,
            'account_name' => $this->emailMessage->emailAccount->name,
            'account_email' => $this->emailMessage->emailAccount->email,
        ];
    }
}
