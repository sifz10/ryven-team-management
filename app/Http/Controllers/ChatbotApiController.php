<?php

namespace App\Http\Controllers;

use App\Models\ChatbotWidget;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotApiController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Initialize chatbot widget - Get conversation or create new one
     * POST /api/chatbot/init
     */
    public function initWidget(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            if (!$token || !str_starts_with($token, 'Bearer ')) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $token = str_replace('Bearer ', '', $token);
            $widget = $this->chatbotService->authenticateWidget($token);

            if (!$widget) {
                return response()->json(['error' => 'Widget not found or inactive'], 404);
            }

            // Get or create conversation
            $conversation = $this->chatbotService->getOrCreateConversation($widget, [
                'visitor_name' => $request->input('visitor_name', 'Guest'),
                'visitor_email' => $request->input('visitor_email'),
                'visitor_phone' => $request->input('visitor_phone'),
                'visitor_ip' => $request->ip(),
                'visitor_metadata' => $request->input('visitor_metadata', []),
            ]);

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'widget_name' => $widget->name,
                'welcome_message' => $widget->welcome_message,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot init error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Send a message
     * POST /api/chatbot/message
     */
    public function sendMessage(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            if (!$token || !str_starts_with($token, 'Bearer ')) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $token = str_replace('Bearer ', '', $token);
            $widget = $this->chatbotService->authenticateWidget($token);

            if (!$widget) {
                return response()->json(['error' => 'Widget not found'], 404);
            }

            $validated = $request->validate([
                'conversation_id' => 'required|exists:chat_conversations,id',
                'message' => 'required|string|max:5000',
                'sender_type' => 'required|in:visitor,employee',
                'sender_id' => 'nullable|integer',
            ]);

            // Verify conversation belongs to this widget
            $conversation = ChatConversation::findOrFail($validated['conversation_id']);
            if ($conversation->chatbot_widget_id !== $widget->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Store message
            $message = $this->chatbotService->storeMessage($conversation, $validated);

            // Broadcast real-time update
            broadcast(new \App\Events\ChatMessageReceived($conversation, $message))->toOthers();

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'timestamp' => $message->created_at->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Get conversation history
     * GET /api/chatbot/conversation/{id}
     */
    public function getConversation(Request $request, ChatConversation $conversation)
    {
        try {
            $token = $request->header('Authorization');
            if (!$token || !str_starts_with($token, 'Bearer ')) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $token = str_replace('Bearer ', '', $token);
            $widget = $this->chatbotService->authenticateWidget($token);

            if (!$widget) {
                return response()->json(['error' => 'Widget not found'], 404);
            }

            // Verify conversation belongs to this widget
            if ($conversation->chatbot_widget_id !== $widget->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Mark visitor messages as read
            $conversation->messages()
                ->where('sender_type', 'visitor')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'data' => $this->chatbotService->getConversationWithMessages($conversation),
            ]);
        } catch (\Exception $e) {
            Log::error('Get conversation error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
