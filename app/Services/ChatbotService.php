<?php

namespace App\Services;

use App\Models\ChatbotWidget;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Str;

class ChatbotService
{
    /**
     * Authenticate widget by token
     */
    public function authenticateWidget(string $token): ?ChatbotWidget
    {
        return ChatbotWidget::where('api_token', $token)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get or create conversation
     */
    public function getOrCreateConversation(ChatbotWidget $widget, array $data)
    {
        $visitorId = $data['visitor_id'] ?? null;
        
        // Try to find existing conversation for this visitor
        if ($visitorId) {
            $conversation = ChatConversation::where('chatbot_widget_id', $widget->id)
                ->where('visitor_email', $data['visitor_email'] ?? null)
                ->whereIn('status', ['active', 'pending'])
                ->first();

            if ($conversation) {
                return $conversation;
            }
        }

        // Create new conversation
        return ChatConversation::create([
            'chatbot_widget_id' => $widget->id,
            'visitor_name' => $data['visitor_name'] ?? 'Visitor',
            'visitor_email' => $data['visitor_email'] ?? null,
            'visitor_phone' => $data['visitor_phone'] ?? null,
            'visitor_ip' => $data['visitor_ip'] ?? null,
            'visitor_metadata' => $data['visitor_metadata'] ?? [],
            'status' => 'pending',
        ]);
    }

    /**
     * Store a message in the conversation
     */
    public function storeMessage(ChatConversation $conversation, array $data): ChatMessage
    {
        // Update conversation status and last message time
        $conversation->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        return ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender_type' => $data['sender_type'], // 'visitor' or 'employee'
            'sender_id' => $data['sender_id'] ?? null,
            'message' => $data['message'],
            'attachment_path' => $data['attachment_path'] ?? null,
            'attachment_name' => $data['attachment_name'] ?? null,
        ]);
    }

    /**
     * Get conversation with messages
     */
    public function getConversationWithMessages(ChatConversation $conversation)
    {
        // Load all relationships efficiently
        $conversation->load(['messages' => function ($query) {
            $query->with(['attachments', 'sender']);
        }]);

        return [
            'id' => $conversation->id,
            'visitor_name' => $conversation->visitor_name,
            'visitor_email' => $conversation->visitor_email,
            'visitor_phone' => $conversation->visitor_phone,
            'status' => $conversation->status,
            'assigned_to' => $conversation->assignedEmployee ? [
                'id' => $conversation->assignedEmployee->id,
                'name' => $conversation->assignedEmployee->first_name . ' ' . $conversation->assignedEmployee->last_name,
            ] : null,
            'messages' => $conversation->messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'sender_name' => $msg->sender_type === 'employee' 
                        ? ($msg->sender ? $msg->sender->first_name . ' ' . $msg->sender->last_name : 'Support')
                        : $msg->conversation->visitor_name,
                    'message' => $msg->message,
                    'attachments' => $msg->attachments->map(fn ($att) => $att->toApiArray())->toArray(),
                    'is_voice' => $msg->is_voice ?? false,
                    'timestamp' => $msg->created_at->format('Y-m-d H:i:s'),
                    'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                    'read_at' => $msg->read_at?->format('Y-m-d H:i:s'),
                ];
            }),
            'created_at' => $conversation->created_at->format('Y-m-d H:i:s'),
            'last_message_at' => $conversation->last_message_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount(ChatConversation $conversation): int
    {
        return $conversation->messages()
            ->where('sender_type', 'visitor')
            ->whereNull('read_at')
            ->count();
    }
}
