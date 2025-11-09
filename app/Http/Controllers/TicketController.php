<?php

namespace App\Http\Controllers;

use App\Models\ProjectTicket;
use App\Models\Project;
use App\Models\Employee;
use App\Models\TicketComment;
use App\Services\TicketNotificationService;
use App\Services\TicketAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectTicket::with(['project', 'assignedTo', 'reportedBy']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by project
        if ($request->filled('project_id') && $request->project_id !== 'all') {
            $query->where('project_id', $request->project_id);
        }

        // Filter by assigned employee
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search by ticket number, title, or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tickets = $query->paginate(20)->appends($request->except('page'));

        // Get all projects and employees for filter dropdowns
        $projects = Project::orderBy('name')->get();
        $employees = Employee::whereNull('deleted_at')->orderBy('first_name')->get();

        return view('tickets.index', compact('tickets', 'projects', 'employees'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $employees = Employee::whereNull('deleted_at')->orderBy('first_name')->get();

        return view('tickets.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bug,feature,enhancement,question',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        // Generate unique ticket number
        $ticketNumber = 'TKT-' . strtoupper(Str::random(8));
        while (ProjectTicket::where('ticket_number', $ticketNumber)->exists()) {
            $ticketNumber = 'TKT-' . strtoupper(Str::random(8));
        }

        $validated['ticket_number'] = $ticketNumber;

        // Set reported_by based on authenticated user
        if (Auth::guard('employee')->check()) {
            $validated['reported_by'] = Auth::guard('employee')->user()->id;
        } elseif (Auth::check()) {
            // If admin is logged in, use first employee as placeholder or handle differently
            $validated['reported_by'] = Employee::first()->id ?? 1;
        } else {
            $validated['reported_by'] = 1; // Default fallback
        }

        $validated['status'] = 'open';

        $ticket = ProjectTicket::create($validated);

        // Send notifications
        $creator = Employee::find($validated['reported_by']);
        if ($creator) {
            app(TicketNotificationService::class)->notifyTicketCreated($ticket->load(['project', 'assignedTo']), $creator);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully!',
            'ticket' => $ticket
        ]);
    }

    public function show(ProjectTicket $ticket)
    {
        $ticket->load(['project', 'assignedTo', 'reportedBy']);
        $employees = Employee::whereNull('deleted_at')->orderBy('first_name')->get();

        // Pre-process employees for Alpine.js to avoid @json conflicts
        $employeesData = $employees->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'username' => strtolower($emp->first_name),
                'initials' => substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1)
            ];
        });

        return view('tickets.show', compact('ticket', 'employees', 'employeesData'));
    }

    public function update(Request $request, ProjectTicket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bug,feature,enhancement,question',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in-progress,resolved,closed',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        $oldStatus = $ticket->status;
        $statusChanged = $oldStatus !== $validated['status'];

        // Set resolved_at timestamp if status changed to resolved
        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] !== 'resolved') {
            $validated['resolved_at'] = null;
        }

        $ticket->update($validated);

        // Send notification if status changed
        if ($statusChanged) {
            $updater = Auth::guard('employee')->check()
                ? Auth::guard('employee')->user()
                : Employee::first();

            if ($updater) {
                app(TicketNotificationService::class)->notifyStatusChanged(
                    $ticket->load(['project', 'assignedTo', 'reportedBy']),
                    $updater,
                    $oldStatus,
                    $validated['status']
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully!',
            'ticket' => $ticket
        ]);
    }

    public function destroy(ProjectTicket $ticket)
    {
        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully!'
        ]);
    }

    public function getComments(ProjectTicket $ticket)
    {
        $comments = $ticket->comments()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($comment) {
                $isAI = $comment->isAI();

                return [
                    'id' => $comment->id,
                    'comment' => str_replace('[AI Assistant] ', '', $comment->comment),
                    'author_name' => $comment->author_name,
                    'author_initials' => $comment->author_initials,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'is_ai' => $isAI,
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    public function storeComment(Request $request, ProjectTicket $ticket)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        // Get current user ID
        $userId = Auth::guard('employee')->check()
            ? Auth::guard('employee')->user()->id
            : (Auth::check() ? Employee::first()->id ?? 1 : 1);

        $comment = $ticket->comments()->create([
            'user_id' => $userId,
            'comment' => $validated['comment'],
        ]);

        // Send notifications
        $commenter = Employee::find($userId);
        if ($commenter) {
            app(TicketNotificationService::class)->notifyCommentAdded(
                $ticket->load(['project', 'assignedTo', 'reportedBy']),
                $commenter,
                $validated['comment']
            );
        }

        // Check if AI should respond
        $aiService = app(TicketAIService::class);
        $aiResponse = null;

        Log::info('AI Response Check', [
            'ticket_id' => $ticket->id,
            'assigned_to' => $ticket->assigned_to,
            'status' => $ticket->status,
            'should_respond' => $aiService->shouldAIRespond($ticket, $comment),
        ]);

        if ($aiService->shouldAIRespond($ticket, $comment)) {
            Log::info('AI is generating response for ticket #' . $ticket->id);
            $aiResult = $aiService->generateResponse($ticket, $validated['comment']);
            Log::info('AI response result', ['has_result' => !is_null($aiResult)]);

            if ($aiResult) {
                // Create AI comment
                $aiComment = $ticket->comments()->create([
                    'user_id' => null, // AI has no user ID
                    'comment' => '[AI Assistant] ' . $aiResult['message'],
                ]);

                // Apply AI suggested actions
                $ticketUpdated = false;

                if ($aiResult['suggested_priority'] && $aiResult['suggested_priority'] !== $ticket->priority) {
                    $ticket->priority = $aiResult['suggested_priority'];
                    $ticketUpdated = true;
                }

                if ($aiResult['suggested_status'] && $aiResult['suggested_status'] !== $ticket->status) {
                    $ticket->status = $aiResult['suggested_status'];
                    $ticketUpdated = true;
                }

                if ($ticketUpdated) {
                    $ticket->save();
                }

                // If escalated, assign to admin or notify team
                if ($aiResult['should_escalate']) {
                    // Mark as needing human attention (you could assign to admin here)
                    app(TicketNotificationService::class)->notifyTicketCreated(
                        $ticket->load(['project', 'assignedTo']),
                        $commenter
                    );
                }

                $aiResponse = [
                    'id' => $aiComment->id,
                    'comment' => $aiComment->comment,
                    'author_name' => 'AI Assistant',
                    'author_initials' => 'AI',
                    'created_at' => $aiComment->created_at->diffForHumans(),
                    'is_ai' => true,
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Comment posted successfully!',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'author_name' => $comment->user->first_name . ' ' . $comment->user->last_name,
                'author_initials' => substr($comment->user->first_name, 0, 1) . substr($comment->user->last_name, 0, 1),
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ];

        // Include AI response if generated
        if ($aiResponse) {
            $response['ai_response'] = $aiResponse;
            $response['ticket_updated'] = $ticketUpdated ?? false;
            if ($ticketUpdated ?? false) {
                $response['ticket'] = [
                    'priority' => $ticket->priority,
                    'status' => $ticket->status,
                ];
            }
        }

        return response()->json($response);
    }

    public function getUnreadNotifications()
    {
        $userId = Auth::guard('employee')->check()
            ? Auth::guard('employee')->user()->id
            : Employee::first()->id;

        $notifications = app(TicketNotificationService::class)->getUnreadNotifications($userId);

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function markNotificationRead($notificationId)
    {
        app(TicketNotificationService::class)->markAsRead($notificationId);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllNotificationsRead()
    {
        $userId = Auth::guard('employee')->check()
            ? Auth::guard('employee')->user()->id
            : Employee::first()->id;

        app(TicketNotificationService::class)->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}
