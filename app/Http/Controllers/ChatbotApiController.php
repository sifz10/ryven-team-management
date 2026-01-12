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

    /**
     * Upload file
     * POST /api/chatbot/file
     */
    public function uploadFile(Request $request)
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
                'file' => 'required|file|max:10240', // 10MB max
                'sender_type' => 'required|in:visitor,employee',
            ]);

            // Verify conversation belongs to this widget
            $conversation = ChatConversation::findOrFail($validated['conversation_id']);
            if ($conversation->chatbot_widget_id !== $widget->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Store file
            $file = $request->file('file');
            $path = $file->store('chatbot-files', 'public');
            $fileName = $file->getClientOriginalName();

            // Store message with attachment
            $message = ChatMessage::create([
                'chat_conversation_id' => $conversation->id,
                'sender_type' => $validated['sender_type'],
                'sender_id' => $widget->id,
                'message' => "📎 {$fileName}",
                'attachment_path' => "/storage/{$path}",
                'attachment_name' => $fileName,
            ]);

            broadcast(new \App\Events\ChatMessageReceived($conversation, $message))->toOthers();

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'file_url' => "/storage/{$path}",
                'timestamp' => $message->created_at->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Upload voice message
     * POST /api/chatbot/voice
     */
    public function uploadVoice(Request $request)
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
                'voice_message' => 'required|file|mimes:webm,mp3,wav,ogg,m4a|max:10240',
                'sender_type' => 'required|in:visitor,employee',
                'message' => 'required|string',
            ]);

            // Verify conversation belongs to this widget
            $conversation = ChatConversation::findOrFail($validated['conversation_id']);
            if ($conversation->chatbot_widget_id !== $widget->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Store voice file
            $file = $request->file('voice_message');
            $fileName = 'voice-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('chatbot-voices', $fileName, 'public');

            // Store message with voice attachment
            $message = ChatMessage::create([
                'chat_conversation_id' => $conversation->id,
                'sender_type' => $validated['sender_type'],
                'sender_id' => $widget->id,
                'message' => $validated['message'],
                'attachment_path' => "/storage/{$path}",
                'attachment_name' => $fileName,
            ]);

            // Mark as voice message
            $message->update(['is_voice' => true]);

            broadcast(new \App\Events\ChatMessageReceived($conversation, $message))->toOthers();

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'file_url' => "/storage/{$path}",
                'timestamp' => $message->created_at->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            Log::error('Voice upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
