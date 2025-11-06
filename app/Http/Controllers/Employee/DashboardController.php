<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the employee dashboard.
     */
    public function index(): View
    {
        $employee = Auth::guard('employee')->user();

        // Load relationships
        $employee->load([
            'payments' => function ($query) {
                $query->latest('paid_at')->limit(10);
            },
            'attendances' => function ($query) {
                $query->latest('date')->limit(30);
            },
            'githubLogs' => function ($query) {
                $query->latest('event_at')->limit(20);
            },
            'dailyChecklists' => function ($query) {
                $query->whereDate('created_at', today())->with('items');
            },
            'performanceReviews' => function ($query) {
                $query->latest()->limit(5);
            },
            'goals' => function ($query) {
                $query->where('status', '!=', 'completed')->latest();
            }
        ]);

        // Get today's checklist if exists
        $todayChecklist = $employee->dailyChecklists->first();

        // Calculate statistics
        $stats = [
            'total_payments' => $employee->payments()->count(),
            'this_month_payments' => $employee->payments()
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', now()->month)
                ->count(),
            'attendance_percentage' => $this->calculateAttendancePercentage($employee),
            'github_activities' => $employee->githubLogs()->count(),
            'active_goals' => $employee->goals()->where('status', '!=', 'completed')->count(),
        ];

        return view('employee.dashboard', compact('employee', 'todayChecklist', 'stats'));
    }

    /**
     * Calculate attendance percentage for current month
     */
    private function calculateAttendancePercentage($employee): float
    {
        $currentMonth = now();
        $totalDays = $currentMonth->daysInMonth;

        // Calculate weekends in current month
        $weekendDays = 0;
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            if ($date->isWeekend()) {
                $weekendDays++;
            }
        }

        $workingDays = $totalDays - $weekendDays;

        $presentDays = $employee->attendances()
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->where('status', 'present')
            ->count();

        return $workingDays > 0 ? round(($presentDays / $workingDays) * 100, 2) : 0;
    }
}
