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
        $eventName = 'ChatMessageReceived';
        \Illuminate\Support\Facades\Log::debug('Event broadcastAs() called', [
            'event_name' => $eventName,
            'conversation_id' => $this->conversation->id,
            'message_id' => $this->message->id,
        ]);
        return $eventName;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat.conversation.' . $this->conversation->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // Load attachments eagerly for broadcast payload
        $this->message->load('attachments');
        
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $this->message->sender_type === 'employee' 
                ? ($this->message->sender ? $this->message->sender->first_name . ' ' . $this->message->sender->last_name : 'Support')
                : $this->conversation->visitor_name,
            'message' => $this->message->message,
            'attachments' => $this->message->attachments
                ->map(fn ($att) => $att->toApiArray())
                ->toArray(),
            'is_voice' => $this->message->is_voice ?? false,
            'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
            'timestamp' => $this->message->created_at->timestamp,
        ];
    }
}
