<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JibbleSyncLog;
use App\Models\JibbleTimeEntry;
use App\Models\JibbleLeaveRequest;
use App\Services\JibbleApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class JibbleController extends Controller
{
    /**
     * Show Jibble dashboard
     */
    public function dashboard()
    {
        $recentSyncs = JibbleSyncLog::recent()->get();
        
        $syncStats = [
            'employees' => JibbleSyncLog::where('sync_type', 'employees')->latest()->first(),
            'time_entries' => JibbleSyncLog::where('sync_type', 'time_entries')->latest()->first(),
            'time_off' => JibbleSyncLog::where('sync_type', 'time_off')->latest()->first(),
        ];

        // Count statistics
        $stats = [
            'total_time_entries' => JibbleTimeEntry::count(),
            'total_leave_requests' => JibbleLeaveRequest::count(),
            'pending_leave' => JibbleLeaveRequest::pending()->count(),
            'approved_leave' => JibbleLeaveRequest::approved()->count(),
        ];

        return view('jibble.dashboard', compact('recentSyncs', 'syncStats', 'stats'));
    }

    /**
     * Test Jibble API connection
     */
    public function testConnection()
    {
        try {
            $jibbleService = new JibbleApiService();
            $isConnected = $jibbleService->testConnection();

            if ($isConnected) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ Jibble credentials configured. API connection ready.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Jibble credentials missing. Please add JIBBLE_ACCESS_TOKEN and JIBBLE_ORGANIZATION_ID to your .env file.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Connection error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trigger employee sync
     */
    public function syncEmployees()
    {
        try {
            Artisan::call('jibble:sync-employees');
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Employee sync started successfully',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trigger time entries sync
     */
    public function syncTimeEntries(Request $request)
    {
        try {
            $days = $request->input('days', 7);
            
            Artisan::call('jibble:sync-time-entries', ['--days' => $days]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Time entries sync started successfully',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trigger leave requests sync
     */
    public function syncLeaveRequests(Request $request)
    {
        try {
            $status = $request->input('status', 'all');
            
            Artisan::call('jibble:sync-leave-requests', ['--status' => $status]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave requests sync started successfully',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show sync history
     */
    public function syncHistory()
    {
        $syncs = JibbleSyncLog::latest()->paginate(20);

        return view('jibble.sync-history', compact('syncs'));
    }

    /**
     * Show time entries
     */
    public function timeEntries(Request $request)
    {
        $query = JibbleTimeEntry::with('employee');

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('clock_in_time', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('clock_in_time', '<=', $request->input('end_date'));
        }

        $entries = $query->latest()->paginate(20);

        $employees = Employee::orderBy('full_name')->get();

        $stats = [
            'total_entries' => JibbleTimeEntry::count(),
            'total_hours' => JibbleTimeEntry::sum(\DB::raw('duration_minutes / 60')),
            'total_employees' => JibbleTimeEntry::distinct('employee_id')->count(),
        ];

        return view('jibble.time-entries', compact('entries', 'employees', 'stats'));
    }

    /**
     * Show leave requests
     */
    public function leaveRequests(Request $request)
    {
        $query = JibbleLeaveRequest::with('employee');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->input('end_date'));
        }

        $requests = $query->latest()->paginate(20);

        $employees = Employee::orderBy('full_name')->get();

        $stats = [
            'total_requests' => JibbleLeaveRequest::count(),
            'pending' => JibbleLeaveRequest::pending()->count(),
            'approved' => JibbleLeaveRequest::approved()->count(),
            'rejected' => JibbleLeaveRequest::where('status', 'rejected')->count(),
        ];

        return view('jibble.leave-requests', compact('requests', 'employees', 'stats'));
    }
}
