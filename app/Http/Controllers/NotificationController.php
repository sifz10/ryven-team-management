<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function page(Request $request)
    {
        $filter = $request->input('filter', 'all'); // all, unread, read
        
        $query = $request->user()->notifications();
        
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = $request->user()->notifications()->whereNull('read_at')->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Get all notifications for the authenticated user (API)
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $notifications->where('read_at', null)->count(),
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // Ensure notification belongs to the authenticated user
        if ($notification->user_id !== $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
            abort(403);
        }

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        }

        return back()->with('status', 'Notification marked as read');
    }

    /**
     * Mark a notification as unread
     */
    public function markAsUnread(Request $request, Notification $notification)
    {
        // Ensure notification belongs to the authenticated user
        if ($notification->user_id !== $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
            abort(403);
        }

        $notification->update(['read_at' => null]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread',
            ]);
        }

        return back()->with('status', 'Notification marked as unread');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, Notification $notification)
    {
        // Ensure notification belongs to the authenticated user
        if ($notification->user_id !== $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
            abort(403);
        }

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted',
            ]);
        }

        return back()->with('status', 'Notification deleted');
    }

    /**
     * Clear all read notifications
     */
    public function clearRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNotNull('read_at')
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All read notifications cleared',
            ]);
        }

        return back()->with('status', 'All read notifications cleared');
    }
}
