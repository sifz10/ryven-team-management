<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\JibbleSyncLog;
use App\Models\JibbleLeaveRequest;
use App\Services\JibbleApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncJibbleLeaveRequests extends Command
{
    protected $signature = 'jibble:sync-leave-requests {--status=all : Filter by status (all, pending, approved)}';
    protected $description = 'Sync leave/time off requests from Jibble API';

    public function handle()
    {
        if (!config('jibble.enabled')) {
            $this->error('Jibble integration is not enabled in configuration');
            return 1;
        }

        $syncLog = JibbleSyncLog::create([
            'sync_type' => 'time_off',
            'status' => 'processing',
            'started_at' => now(),
        ]);

        try {
            $this->info('Starting leave requests sync from Jibble...');
            
            $jibbleService = new JibbleApiService();
            
            $filters = [];
            $status = $this->option('status');
            if ($status !== 'all') {
                $filters['status'] = $status;
            }

            $leaveRequests = $jibbleService->getTimeOffRequests($filters);

            if (empty($leaveRequests)) {
                $this->warn('No leave requests found from Jibble API');
                $syncLog->update([
                    'status' => 'completed',
                    'records_synced' => 0,
                    'completed_at' => now(),
                ]);
                return 0;
            }

            $synced = 0;
            $failed = 0;

            foreach ($leaveRequests as $request) {
                try {
                    $this->syncLeaveRequest($request);
                    $synced++;
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Failed to sync Jibble leave request: {$request['id']}", [
                        'error' => $e->getMessage(),
                        'request' => $request,
                    ]);
                }
            }

            $syncLog->update([
                'status' => 'completed',
                'records_synced' => $synced,
                'records_failed' => $failed,
                'completed_at' => now(),
            ]);

            $this->info("✅ Leave requests sync completed! Synced: {$synced}, Failed: {$failed}");
            return 0;

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::error('Jibble leave requests sync failed: ' . $errorMsg);
            
            $syncLog->update([
                'status' => 'failed',
                'error_message' => $errorMsg,
                'completed_at' => now(),
            ]);

            $this->error("❌ Sync failed: {$errorMsg}");
            return 1;
        }
    }

    /**
     * Sync a single leave request from Jibble data
     */
    private function syncLeaveRequest(array $request): void
    {
        $jibbleRequestId = $request['id'];
        $memberId = $request['memberId'];

        // Find employee by Jibble ID
        $employee = Employee::where('jibble_id', $memberId)->first();
        
        if (!$employee) {
            throw new \Exception("Employee with Jibble ID {$memberId} not found");
        }

        // Check if already synced
        $existingRequest = JibbleLeaveRequest::where('jibble_request_id', $jibbleRequestId)->first();

        $leaveType = $request['type'] ?? 'vacation';
        $status = $request['status'] ?? 'pending';

        if (!$existingRequest) {
            // Create new leave request
            JibbleLeaveRequest::create([
                'employee_id' => $employee->id,
                'jibble_request_id' => $jibbleRequestId,
                'start_date' => $request['startDate'],
                'end_date' => $request['endDate'],
                'status' => $status,
                'leave_type' => $leaveType,
                'reason' => $request['reason'] ?? null,
                'notes' => $request['notes'] ?? null,
                'days_count' => $request['daysCount'] ?? null,
                'jibble_data' => $request,
                'synced_at' => now(),
            ]);

            $this->line("✓ Created leave request for {$employee->first_name} {$employee->last_name}");
        } else {
            // Update existing request (status changes, etc.)
            $existingRequest->update([
                'status' => $status,
                'reason' => $request['reason'] ?? $existingRequest->reason,
                'notes' => $request['notes'] ?? $existingRequest->notes,
                'days_count' => $request['daysCount'] ?? $existingRequest->days_count,
                'jibble_data' => $request,
                'synced_at' => now(),
            ]);

            $this->line("✓ Updated leave request for {$employee->first_name} {$employee->last_name}");
        }
    }
}
