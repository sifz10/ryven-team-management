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
use Illuminate\Support\Facades\Log;

class ChatMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    // Private model instances (NOT sent to Pusher)
    private ChatConversation $conversation;
    private ChatMessage $message;
    
    // Public scalar properties (SENT to Pusher)
    public $id;
    public $conversation_id;
    public $sender_type;
    public $sender_name;
    public $messageText;
    public $attachments;
    public $is_voice;
    public $created_at;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatConversation $conversation, ChatMessage $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
        
        // Load attachments
        $message->load('attachments');
        
        // Pre-populate public properties for broadcast
        $this->id = $message->id;
        $this->conversation_id = $conversation->id;
        $this->sender_type = $message->sender_type;
        $this->sender_name = $message->sender_type === 'employee' 
            ? ($message->sender ? $message->sender->first_name . ' ' . $message->sender->last_name : 'Support')
            : $conversation->visitor_name;
        $this->messageText = $message->message;
        $this->attachments = $message->attachments
            ->map(fn ($att) => $att->toApiArray())
            ->toArray();
        $this->is_voice = $message->is_voice ?? false;
        $this->created_at = $message->created_at->format('Y-m-d H:i:s');
        $this->timestamp = $message->created_at->timestamp;
        
        Log::info('ChatMessageReceived event created', [
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'sender_type' => $message->sender_type,
            'has_payload' => true,
        ]);
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
            new Channel('chat.conversation.' . $this->conversation->id),
        ];
    }
}
