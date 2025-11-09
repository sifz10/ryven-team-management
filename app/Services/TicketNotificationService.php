<?php

namespace App\Services;

use App\Models\ProjectTicket;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use App\Mail\TicketNotificationMail;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TicketNotificationService
{
    /**
     * Send notification when ticket is created
     */
    public function notifyTicketCreated(ProjectTicket $ticket, Employee $creator): void
    {
        // Notify admin users
        $admins = User::all();
        foreach ($admins as $admin) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $admin->id, // This needs to map to employee
                type: 'created',
                message: "{$creator->first_name} {$creator->last_name} created a new ticket",
                triggeredBy: $creator
            );
        }

        // Notify assigned employee
        if ($ticket->assigned_to) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $ticket->assigned_to,
                type: 'assigned',
                message: "You have been assigned to ticket {$ticket->ticket_number}",
                triggeredBy: $creator
            );
        }
    }

    /**
     * Send notification when comment is added
     */
    public function notifyCommentAdded(ProjectTicket $ticket, Employee $commenter, string $commentText): void
    {
        $notifiedUsers = [];

        // Notify ticket creator
        if ($ticket->reported_by && $ticket->reported_by !== $commenter->id) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $ticket->reported_by,
                type: 'replied',
                message: "{$commenter->first_name} {$commenter->last_name} replied to your ticket",
                triggeredBy: $commenter,
                metadata: ['comment' => $commentText]
            );
            $notifiedUsers[] = $ticket->reported_by;
        }

        // Notify assigned employee
        if ($ticket->assigned_to && $ticket->assigned_to !== $commenter->id && !in_array($ticket->assigned_to, $notifiedUsers)) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $ticket->assigned_to,
                type: 'replied',
                message: "{$commenter->first_name} {$commenter->last_name} added a comment",
                triggeredBy: $commenter,
                metadata: ['comment' => $commentText]
            );
            $notifiedUsers[] = $ticket->assigned_to;
        }

        // Check for mentions (@username)
        $mentions = $this->extractMentions($commentText);
        foreach ($mentions as $mentionedUserId) {
            if ($mentionedUserId !== $commenter->id && !in_array($mentionedUserId, $notifiedUsers)) {
                $this->sendNotification(
                    ticket: $ticket,
                    userId: $mentionedUserId,
                    type: 'mentioned',
                    message: "{$commenter->first_name} {$commenter->last_name} mentioned you in a comment",
                    triggeredBy: $commenter,
                    metadata: ['comment' => $commentText]
                );
                $notifiedUsers[] = $mentionedUserId;
            }
        }

        // Notify admins (using first employee as placeholder for admin mapping)
        $adminEmployees = Employee::whereNull('deleted_at')->take(1)->get(); // Adjust based on your admin detection
        foreach ($adminEmployees as $admin) {
            if ($admin->id !== $commenter->id && !in_array($admin->id, $notifiedUsers)) {
                $this->sendNotification(
                    ticket: $ticket,
                    userId: $admin->id,
                    type: 'replied',
                    message: "New comment on ticket {$ticket->ticket_number}",
                    triggeredBy: $commenter,
                    metadata: ['comment' => $commentText]
                );
            }
        }
    }

    /**
     * Send notification when ticket status changes
     */
    public function notifyStatusChanged(ProjectTicket $ticket, Employee $updater, string $oldStatus, string $newStatus): void
    {
        $notifiedUsers = [];

        // Notify ticket creator
        if ($ticket->reported_by && $ticket->reported_by !== $updater->id) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $ticket->reported_by,
                type: 'status_changed',
                message: "Ticket status changed from {$oldStatus} to {$newStatus}",
                triggeredBy: $updater,
                metadata: ['old_status' => $oldStatus, 'new_status' => $newStatus]
            );
            $notifiedUsers[] = $ticket->reported_by;
        }

        // Notify assigned employee
        if ($ticket->assigned_to && $ticket->assigned_to !== $updater->id && !in_array($ticket->assigned_to, $notifiedUsers)) {
            $this->sendNotification(
                ticket: $ticket,
                userId: $ticket->assigned_to,
                type: 'status_changed',
                message: "Ticket status changed from {$oldStatus} to {$newStatus}",
                triggeredBy: $updater,
                metadata: ['old_status' => $oldStatus, 'new_status' => $newStatus]
            );
            $notifiedUsers[] = $ticket->assigned_to;
        }

        // Notify admins
        $adminEmployees = Employee::whereNull('deleted_at')->take(1)->get();
        foreach ($adminEmployees as $admin) {
            if ($admin->id !== $updater->id && !in_array($admin->id, $notifiedUsers)) {
                $this->sendNotification(
                    ticket: $ticket,
                    userId: $admin->id,
                    type: 'status_changed',
                    message: "Ticket {$ticket->ticket_number} status changed to {$newStatus}",
                    triggeredBy: $updater,
                    metadata: ['old_status' => $oldStatus, 'new_status' => $newStatus]
                );
            }
        }
    }

    /**
     * Send notification to specific user
     */
    private function sendNotification(
        ProjectTicket $ticket,
        int $userId,
        string $type,
        string $message,
        ?Employee $triggeredBy = null,
        ?array $metadata = null
    ): void {
        try {
            // Find employee
            $employee = Employee::find($userId);
            if (!$employee) {
                return;
            }

            // Find associated user account
            $user = User::where('email', $employee->email)->first();
            if (!$user) {
                // If no user account, try to find admin user for this employee
                $user = User::first(); // Fallback to first admin
            }

            if ($user) {
                // Create in-app notification using existing notifications table
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'type' => 'ticket',
                    'title' => 'Ticket ' . ucfirst(str_replace('_', ' ', $type)),
                    'message' => $message,
                    'icon' => '🎫',
                    'data' => [
                        'ticket_id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'notification_type' => $type,
                        'triggered_by' => $triggeredBy?->id,
                        'triggered_by_name' => $triggeredBy ? $triggeredBy->first_name . ' ' . $triggeredBy->last_name : null,
                        'metadata' => $metadata,
                    ],
                ]);

                // Broadcast real-time notification
                broadcast(new NewNotification($notification));
            }

            // Send email notification
            if ($employee->email) {
                Mail::to($employee->email)->send(
                    new TicketNotificationMail(
                        ticket: $ticket,
                        notificationType: $type,
                        message: $message,
                        triggeredBy: $triggeredBy,
                        commentText: $metadata['comment'] ?? null
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send ticket notification: ' . $e->getMessage());
        }
    }

    /**
     * Extract user mentions from comment text
     * Supports @username format
     */
    private function extractMentions(string $text): array
    {
        preg_match_all('/@(\w+)/', $text, $matches);

        if (empty($matches[1])) {
            return [];
        }

        $mentionedUserIds = [];
        foreach ($matches[1] as $username) {
            // Try to find employee by first name, last name, or email prefix
            $employee = Employee::whereNull('deleted_at')
                ->where(function ($query) use ($username) {
                    $query->where('first_name', 'like', $username . '%')
                        ->orWhere('last_name', 'like', $username . '%')
                        ->orWhere('email', 'like', $username . '%');
                })
                ->first();

            if ($employee) {
                $mentionedUserIds[] = $employee->id;
            }
        }

        return array_unique($mentionedUserIds);
    }

    /**
     * Get unread notifications for user
     */
    public function getUnreadNotifications(int $userId, int $limit = 10): array
    {
        // Find user by employee ID or directly
        $user = User::find($userId);
        if (!$user) {
            $employee = Employee::find($userId);
            if ($employee) {
                $user = User::where('email', $employee->email)->first();
            }
        }

        if (!$user) {
            return [];
        }

        return Notification::where('user_id', $user->id)
            ->where('type', 'ticket')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data ?? [];
                return [
                    'id' => $notification->id,
                    'type' => $data['notification_type'] ?? 'ticket',
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'ticket_number' => $data['ticket_number'] ?? 'N/A',
                    'ticket_id' => $data['ticket_id'] ?? null,
                    'triggered_by' => $data['triggered_by_name'] ?? 'System',
                    'icon' => $notification->icon,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            })
            ->toArray();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(int $userId): void
    {
        // Find user by employee ID or directly
        $user = User::find($userId);
        if (!$user) {
            $employee = Employee::find($userId);
            if ($employee) {
                $user = User::where('email', $employee->email)->first();
            }
        }

        if ($user) {
            Notification::where('user_id', $user->id)
                ->where('type', 'ticket')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }
}
