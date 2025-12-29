<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\EmploymentContract;
use App\Models\Attendance;
use App\Models\GitHubLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $employeesCount = Employee::whereNull('discontinued_at')->count();
        $discontinuedCount = Employee::whereNotNull('discontinued_at')->count();
        $totalEmployees = Employee::count();
        
        // Monthly Payroll by Currency
        $monthlyByCurrency = Employee::whereNull('discontinued_at')
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(salary) as total')
            ->groupBy('currency')
            ->get();
        
        // Payments Last 30 Days by Currency
        $paid30ByCurrency = EmployeePayment::where('paid_at', '>=', now()->subDays(30))
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(amount) as total')
            ->groupBy('currency')
            ->get();
        
        // Payment Counts
        $paymentsMonthCount = EmployeePayment::whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $paymentsLastMonthCount = EmployeePayment::whereBetween('paid_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $paymentTrend = $paymentsLastMonthCount > 0 ? round((($paymentsMonthCount - $paymentsLastMonthCount) / $paymentsLastMonthCount) * 100, 1) : 0;
        
        // Recent Payments
        $paymentsRecent = EmployeePayment::with('employee')
            ->orderByDesc('paid_at')
            ->limit(5)
            ->get();
        
        // New Hires
        $newHires = Employee::whereNull('discontinued_at')
            ->whereNotNull('hired_at')
            ->where('hired_at', '>=', now()->subDays(30))
            ->count();
        
        // Contracts
        $contractsCount = EmploymentContract::count();
        $activeContractsCount = EmploymentContract::where('status', 'active')->count();
        $draftContractsCount = EmploymentContract::where('status', 'draft')->count();
        $recentContracts = EmploymentContract::with('employee')->latest()->limit(5)->get();
        
        // ========================================
        // ADVANCED ANALYTICS
        // ========================================
        
        // 1. Employee Performance Trends (Last 6 Months)
        $performanceTrends = $this->getPerformanceTrends();
        
        // 2. Department Analytics
        $departmentAnalytics = $this->getDepartmentAnalytics();
        
        // 3. Payment Analytics by Activity Type
        $paymentsByType = EmployeePayment::select('activity_type', DB::raw('COUNT(*) as count'))
            ->whereNotNull('activity_type')
            ->groupBy('activity_type')
            ->get();
        
        // 4. Attendance Overview (Last 30 Days)
        $attendanceStats = $this->getAttendanceStats();
        
        // 5. GitHub Activity Statistics (Last 30 Days)
        $githubStats = $this->getGitHubStats();
        
        // 6. Employee Growth Chart (Last 12 Months)
        $employeeGrowth = $this->getEmployeeGrowthChart();
        
        // 7. Payment History Chart (Last 12 Months)
        $paymentHistory = $this->getPaymentHistoryChart();
        
        // 8. Top Performers (Most Achievements)
        $topPerformers = EmployeePayment::with('employee')
            ->selectRaw('employee_payments.*, COUNT(*) as achievement_count')
            ->where('activity_type', 'achievement')
            ->where('paid_at', '>=', now()->subMonths(3))
            ->groupBy('employee_payments.id')
            ->orderByDesc('achievement_count')
            ->limit(5)
            ->get();
        
        // 9. Recent Activity Timeline
        $recentActivities = EmployeePayment::with('employee')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => $payment->activity_type ?? 'payment',
                    'employee' => $payment->employee,
                    'description' => $payment->note ?? 'Payment recorded',
                    'amount' => $payment->amount,
                    'currency' => $payment->currency ?? ($payment->employee->currency ?? 'USD'),
                    'date' => $payment->created_at,
                    'icon' => match($payment->activity_type ?? 'payment') {
                        'achievement' => '🏆',
                        'warning' => '⚠️',
                        'payment' => '💰',
                        default => '📝'
                    }
                ];
            });
        
        // 10. Salary Distribution by Range
        $salaryDistribution = $this->getSalaryDistribution();
        
        // 11. Attendance Rate by Department
        $attendanceByDepartment = $this->getAttendanceByDepartment();
        
        // 12. Average Tenure by Department
        $avgTenureByDept = $this->getAverageTenure();
        
        return view('dashboard', compact(
            'employeesCount',
            'discontinuedCount',
            'totalEmployees',
            'monthlyByCurrency',
            'paid30ByCurrency',
            'paymentsMonthCount',
            'paymentsLastMonthCount',
            'paymentTrend',
            'paymentsRecent',
            'newHires',
            'contractsCount',
            'activeContractsCount',
            'draftContractsCount',
            'recentContracts',
            'recentActivities',
            // Advanced Analytics
            'performanceTrends',
            'departmentAnalytics',
            'paymentsByType',
            'attendanceStats',
            'githubStats',
            'employeeGrowth',
            'paymentHistory',
            'topPerformers',
            'salaryDistribution',
            'attendanceByDepartment',
            'avgTenureByDept'
        ));
    }
    
    private function getPerformanceTrends()
    {
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $achievements = EmployeePayment::where('activity_type', 'achievement')
                ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $warnings = EmployeePayment::where('activity_type', 'warning')
                ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $trends[] = [
                'month' => $month->format('M Y'),
                'achievements' => $achievements,
                'warnings' => $warnings,
            ];
        }
        
        return $trends;
    }
    
    private function getDepartmentAnalytics()
    {
        return Employee::select('department', DB::raw('COUNT(*) as employee_count'), DB::raw('AVG(salary) as avg_salary'))
            ->whereNull('discontinued_at')
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderByDesc('employee_count')
            ->get()
            ->map(function($dept) {
                // Get activity counts for this department
                $employeeIds = Employee::where('department', $dept->department)
                    ->whereNull('discontinued_at')
                    ->pluck('id');
                
                $achievements = EmployeePayment::whereIn('employee_id', $employeeIds)
                    ->where('activity_type', 'achievement')
                    ->where('paid_at', '>=', now()->subMonths(3))
                    ->count();
                
                $warnings = EmployeePayment::whereIn('employee_id', $employeeIds)
                    ->where('activity_type', 'warning')
                    ->where('paid_at', '>=', now()->subMonths(3))
                    ->count();
                
                return [
                    'department' => $dept->department,
                    'employee_count' => $dept->employee_count,
                    'avg_salary' => round($dept->avg_salary ?? 0, 2),
                    'achievements' => $achievements,
                    'warnings' => $warnings,
                ];
            });
    }
    
    private function getAttendanceStats()
    {
        $totalDays = Attendance::where('date', '>=', now()->subDays(30))->distinct('date')->count('date');
        $presentCount = Attendance::where('status', 'present')
            ->where('date', '>=', now()->subDays(30))
            ->count();
        $absentCount = Attendance::where('status', 'absent')
            ->where('date', '>=', now()->subDays(30))
            ->count();
        $lateCount = Attendance::where('status', 'late')
            ->where('date', '>=', now()->subDays(30))
            ->count();
        
        $totalRecords = $presentCount + $absentCount + $lateCount;
        
        return [
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'total_records' => $totalRecords,
            'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0,
        ];
    }
    
    private function getGitHubStats()
    {
        $pushes = GitHubLog::where('event_type', 'push')
            ->where('event_at', '>=', now()->subDays(30))
            ->count();
        
        $prs = GitHubLog::where('event_type', 'pull_request')
            ->where('event_at', '>=', now()->subDays(30))
            ->count();
        
        $reviews = GitHubLog::where('event_type', 'pull_request_review')
            ->where('event_at', '>=', now()->subDays(30))
            ->count();
        
        $totalCommits = GitHubLog::where('event_type', 'push')
            ->where('event_at', '>=', now()->subDays(30))
            ->sum('commits_count');
        
        // Most active contributors
        $topContributors = GitHubLog::select('employee_id', DB::raw('COUNT(*) as activity_count'))
            ->where('event_at', '>=', now()->subDays(30))
            ->whereNotNull('employee_id')
            ->groupBy('employee_id')
            ->orderByDesc('activity_count')
            ->limit(5)
            ->with('employee')
            ->get();
        
        return [
            'pushes' => $pushes,
            'pull_requests' => $prs,
            'reviews' => $reviews,
            'total_commits' => $totalCommits,
            'top_contributors' => $topContributors,
        ];
    }
    
    private function getEmployeeGrowthChart()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Employee::where('hired_at', '<=', $month->endOfMonth())
                ->where(function($q) use ($month) {
                    $q->whereNull('discontinued_at')
                      ->orWhere('discontinued_at', '>', $month->endOfMonth());
                })
                ->count();
            
            $data[] = [
                'month' => $month->format('M Y'),
                'count' => $count,
            ];
        }
        
        return $data;
    }
    
    private function getPaymentHistoryChart()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $amount = EmployeePayment::whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            
            $count = EmployeePayment::whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $data[] = [
                'month' => $month->format('M Y'),
                'amount' => $amount,
                'count' => $count,
            ];
        }
        
        return $data;
    }
    
    private function getSalaryDistribution()
    {
        $ranges = [
            ['min' => 0, 'max' => 500, 'label' => '0-500'],
            ['min' => 501, 'max' => 1000, 'label' => '501-1K'],
            ['min' => 1001, 'max' => 2000, 'label' => '1K-2K'],
            ['min' => 2001, 'max' => 5000, 'label' => '2K-5K'],
            ['min' => 5001, 'max' => 999999, 'label' => '5K+'],
        ];
        
        $distribution = [];
        foreach ($ranges as $range) {
            $count = Employee::whereNull('discontinued_at')
                ->whereBetween('salary', [$range['min'], $range['max']])
                ->count();
            
            $distribution[] = [
                'range' => $range['label'],
                'count' => $count,
            ];
        }
        
        return $distribution;
    }
    
    private function getAttendanceByDepartment()
    {
        $departments = Employee::whereNull('discontinued_at')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');
        
        $data = [];
        foreach ($departments as $dept) {
            $employeeIds = Employee::where('department', $dept)
                ->whereNull('discontinued_at')
                ->pluck('id');
            
            $totalRecords = Attendance::whereIn('employee_id', $employeeIds)
                ->where('date', '>=', now()->subDays(30))
                ->count();
            
            $presentRecords = Attendance::whereIn('employee_id', $employeeIds)
                ->where('status', 'present')
                ->where('date', '>=', now()->subDays(30))
                ->count();
            
            $rate = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100, 1) : 0;
            
            $data[] = [
                'department' => $dept,
                'attendance_rate' => $rate,
                'total_records' => $totalRecords,
            ];
        }
        
        return collect($data)->sortByDesc('attendance_rate')->values();
    }
    
    private function getAverageTenure()
    {
        return Employee::select('department', DB::raw('AVG(DATEDIFF(COALESCE(discontinued_at, NOW()), hired_at)) as avg_days'))
            ->whereNull('discontinued_at')
            ->whereNotNull('department')
            ->whereNotNull('hired_at')
            ->groupBy('department')
            ->get()
            ->map(function($dept) {
                $years = round(($dept->avg_days ?? 0) / 365, 1);
                return [
                    'department' => $dept->department,
                    'avg_tenure_years' => $years,
                ];
            });
    }
}
