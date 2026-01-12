<?php

namespace App\Events;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatConversation $conversation;
    public ChatMessage $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatConversation $conversation, ChatMessage $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * Get the name of the broadcast event.
     */
    public function broadcastAs(): string
    {
        return 'ChatMessageReceived';
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.conversation.' . $this->conversation->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $this->message->sender_type === 'employee' 
                ? ($this->message->sender ? $this->message->sender->first_name . ' ' . $this->message->sender->last_name : 'Support')
                : $this->conversation->visitor_name,
            'message' => $this->message->message,
            'attachment_path' => $this->message->attachment_path,
            'attachment_name' => $this->message->attachment_name,
            'is_voice' => $this->message->is_voice ?? false,
            'timestamp' => $this->message->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
