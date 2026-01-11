<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotWidget;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Employee;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Dashboard - All conversations
     */
    public function index(Request $request)
    {
        $query = ChatConversation::with('chatbotWidget', 'assignedEmployee')
            ->latest('last_message_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by widget
        if ($request->filled('widget_id')) {
            $query->where('chatbot_widget_id', $request->input('widget_id'));
        }

        // Filter by assigned employee
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to_employee_id', $request->input('assigned_to'));
        }

        $conversations = $query->paginate(20);
        $widgets = ChatbotWidget::where('is_active', true)->get();
        $employees = Employee::where('is_active', true)->get();

        $stats = [
            'total' => ChatConversation::count(),
            'pending' => ChatConversation::where('status', 'pending')->count(),
            'active' => ChatConversation::where('status', 'active')->count(),
            'closed' => ChatConversation::where('status', 'closed')->count(),
            'unread' => ChatMessage::whereHas('conversation', function ($q) {
                $q->whereIn('status', ['pending', 'active']);
            })->where('sender_type', 'visitor')->whereNull('read_at')->count(),
        ];

        return view('admin.chatbot.index', compact('conversations', 'widgets', 'employees', 'stats'));
    }

    /**
     * View single conversation
     */
    public function show(ChatConversation $conversation)
    {
        $conversation->load(['messages', 'chatbotWidget', 'assignedEmployee']);
        
        // Mark all unread visitor messages as read
        $conversation->messages()
            ->where('sender_type', 'visitor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $employees = Employee::where('is_active', true)->get();

        return view('admin.chatbot.show', compact('conversation', 'employees'));
    }

    /**
     * Send reply message
     */
    public function sendReply(Request $request, ChatConversation $conversation)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'employee',
            'sender_id' => auth()->guard('web')->id(),
            'message' => $validated['message'],
        ]);

        $conversation->update([
            'status' => 'active',
            'last_message_at' => now(),
            'assigned_to_employee_id' => auth()->guard('web')->user()->employee?->id,
        ]);

        // Broadcast to widget in real-time
        broadcast(new \App\Events\ChatMessageReceived($conversation, $message))->toOthers();

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
            'timestamp' => $message->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Assign conversation to employee
     */
    public function assign(Request $request, ChatConversation $conversation)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $conversation->update([
            'assigned_to_employee_id' => $validated['employee_id'],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Close conversation
     */
    public function close(ChatConversation $conversation)
    {
        $conversation->update(['status' => 'closed']);

        return response()->json(['success' => true]);
    }

    /**
     * Delete conversation
     */
    public function destroy(ChatConversation $conversation)
    {
        $conversation->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Installation & Customization Guide
     */
    public function guide()
    {
        $widgets = ChatbotWidget::where('is_active', true)->get();
        
        return view('admin.chatbot.guide', compact('widgets'));
    }

    /**
     * Widgets Management - List all widgets
     */
    public function widgetsIndex()
    {
        $widgets = ChatbotWidget::with('conversations')->latest()->paginate(12);
        $stats = [
            'total' => ChatbotWidget::count(),
            'active' => ChatbotWidget::where('is_active', true)->count(),
            'inactive' => ChatbotWidget::where('is_active', false)->count(),
        ];

        return view('admin.chatbot.widgets.index', compact('widgets', 'stats'));
    }

    /**
     * Create new widget form
     */
    public function widgetsCreate()
    {
        return view('admin.chatbot.widgets.create');
    }

    /**
     * Store new widget
     */
    public function widgetsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:chatbot_widgets,domain',
            'welcome_message' => 'required|string|max:500',
            'is_active' => 'boolean',
        ]);

        $widget = ChatbotWidget::create($validated);

        return redirect()->route('admin.chatbot.widgets.show', $widget)
            ->with('success', 'Widget created successfully!');
    }

    /**
     * Edit widget form
     */
    public function widgetsEdit(ChatbotWidget $widget)
    {
        $stats = [
            'conversations' => $widget->conversations()->count(),
            'messages' => ChatMessage::whereHas('conversation', function ($q) use ($widget) {
                $q->where('chatbot_widget_id', $widget->id);
            })->count(),
        ];

        return view('admin.chatbot.widgets.edit', compact('widget', 'stats'));
    }

    /**
     * Update widget
     */
    public function widgetsUpdate(Request $request, ChatbotWidget $widget)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:chatbot_widgets,domain,' . $widget->id,
            'welcome_message' => 'required|string|max:500',
            'installed_in' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $widget->update($validated);

        return redirect()->route('admin.chatbot.widgets.show', $widget)
            ->with('success', 'Widget updated successfully!');
    }

    /**
     * Show widget details
     */
    public function widgetsShow(ChatbotWidget $widget)
    {
        $widget->load('conversations');
        
        $stats = [
            'conversations' => $widget->conversations()->count(),
            'pending' => $widget->conversations()->where('status', 'pending')->count(),
            'active' => $widget->conversations()->where('status', 'active')->count(),
            'closed' => $widget->conversations()->where('status', 'closed')->count(),
            'messages' => ChatMessage::whereHas('conversation', function ($q) use ($widget) {
                $q->where('chatbot_widget_id', $widget->id);
            })->count(),
        ];

        $recentConversations = $widget->conversations()->latest()->limit(5)->get();

        return view('admin.chatbot.widgets.show', compact('widget', 'stats', 'recentConversations'));
    }

    /**
     * Regenerate widget token
     */
    public function widgetsRotateToken(ChatbotWidget $widget)
    {
        $widget->generateNewToken();

        return back()->with('success', 'Token rotated successfully!');
    }

    /**
     * Delete widget
     */
    public function widgetsDestroy(ChatbotWidget $widget)
    {
        $conversationCount = $widget->conversations()->count();

        if ($conversationCount > 0) {
            return back()->with('error', "Cannot delete widget with $conversationCount conversations. Archive instead.");
        }

        $widget->delete();

        return redirect()->route('admin.chatbot.widgets.index')
            ->with('success', 'Widget deleted successfully!');
    }
}
