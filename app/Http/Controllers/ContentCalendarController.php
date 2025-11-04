<?php

namespace App\Http\Controllers;

use App\Models\SocialPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ContentCalendarController extends Controller
{
    /**
     * Display the content calendar
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = Carbon::parse($month . '-01');
        
        // Get posts for the current month
        $posts = Auth::user()->socialPosts()
            ->with('socialAccount')
            ->whereYear('scheduled_at', $date->year)
            ->whereMonth('scheduled_at', $date->month)
            ->orderBy('scheduled_at')
            ->get();
        
        // Group posts by date
        $calendar = $posts->groupBy(function($post) {
            return $post->scheduled_at ? $post->scheduled_at->format('Y-m-d') : null;
        });
        
        $accounts = Auth::user()->socialAccounts;
        
        return view('social.calendar.index', compact('calendar', 'date', 'accounts'));
    }

    /**
     * Get posts for a specific date (AJAX)
     */
    public function getPostsForDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        
        $posts = Auth::user()->socialPosts()
            ->with('socialAccount')
            ->whereDate('scheduled_at', $validated['date'])
            ->orderBy('scheduled_at')
            ->get();
        
        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    /**
     * Get calendar data for month view (AJAX)
     */
    public function getMonthData(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);
        
        $date = Carbon::parse($validated['month'] . '-01');
        
        $posts = Auth::user()->socialPosts()
            ->with('socialAccount')
            ->whereYear('scheduled_at', $date->year)
            ->whereMonth('scheduled_at', $date->month)
            ->orderBy('scheduled_at')
            ->get();
        
        // Group by date and count
        $calendarData = [];
        foreach ($posts as $post) {
            $dateKey = $post->scheduled_at->format('Y-m-d');
            if (!isset($calendarData[$dateKey])) {
                $calendarData[$dateKey] = [
                    'count' => 0,
                    'statuses' => [],
                ];
            }
            $calendarData[$dateKey]['count']++;
            $calendarData[$dateKey]['statuses'][] = $post->status;
        }
        
        return response()->json([
            'success' => true,
            'data' => $calendarData,
        ]);
    }
}
