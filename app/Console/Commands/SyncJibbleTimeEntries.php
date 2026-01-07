<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\JibbleSyncLog;
use App\Models\JibbleTimeEntry;
use App\Models\Attendance;
use App\Services\JibbleApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncJibbleTimeEntries extends Command
{
    protected $signature = 'jibble:sync-time-entries {--days=7 : Number of days to sync}';
    protected $description = 'Sync time entries (clock in/out) from Jibble API';

    public function handle()
    {
        if (!config('jibble.enabled')) {
            $this->error('Jibble integration is not enabled in configuration');
            return 1;
        }

        $days = $this->option('days') ?? config('jibble.sync.time_entries.lookback_days', 7);
        
        $syncLog = JibbleSyncLog::create([
            'sync_type' => 'time_entries',
            'status' => 'processing',
            'started_at' => now(),
        ]);

        try {
            $this->info("Syncing time entries from last {$days} days...");
            
            $endDate = Carbon::now()->format('Y-m-d');
            $startDate = Carbon::now()->subDays($days)->format('Y-m-d');

            $jibbleService = new JibbleApiService();
            $timeEntries = $jibbleService->getTimeEntries($startDate, $endDate);

            if (empty($timeEntries)) {
                $this->warn('No time entries found from Jibble API');
                $syncLog->update([
                    'status' => 'completed',
                    'records_synced' => 0,
                    'completed_at' => now(),
                ]);
                return 0;
            }

            $synced = 0;
            $failed = 0;

            foreach ($timeEntries as $entry) {
                try {
                    $this->syncTimeEntry($entry);
                    $synced++;
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Failed to sync Jibble time entry: {$entry['id']}", [
                        'error' => $e->getMessage(),
                        'entry' => $entry,
                    ]);
                }
            }

            $syncLog->update([
                'status' => 'completed',
                'records_synced' => $synced,
                'records_failed' => $failed,
                'completed_at' => now(),
            ]);

            $this->info("✅ Time entries sync completed! Synced: {$synced}, Failed: {$failed}");
            return 0;

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::error('Jibble time entries sync failed: ' . $errorMsg);
            
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
     * Sync a single time entry from Jibble data
     */
    private function syncTimeEntry(array $entry): void
    {
        $jibbleEntryId = $entry['id'];
        $memberId = $entry['memberId'];

        // Find employee by Jibble ID
        $employee = Employee::where('jibble_id', $memberId)->first();
        
        if (!$employee) {
            throw new \Exception("Employee with Jibble ID {$memberId} not found");
        }

        // Check if already synced
        $existingEntry = JibbleTimeEntry::where('jibble_entry_id', $jibbleEntryId)->first();

        $clockInTime = Carbon::parse($entry['clockInTime']);
        $clockOutTime = $entry['clockOutTime'] ? Carbon::parse($entry['clockOutTime']) : null;
        
        $durationMinutes = null;
        if ($clockOutTime) {
            $durationMinutes = $clockOutTime->diffInMinutes($clockInTime);
        }

        if (!$existingEntry) {
            // Create new time entry
            $timeEntry = JibbleTimeEntry::create([
                'employee_id' => $employee->id,
                'jibble_entry_id' => $jibbleEntryId,
                'clock_in_time' => $clockInTime,
                'clock_out_time' => $clockOutTime,
                'duration_minutes' => $durationMinutes,
                'notes' => $entry['note'] ?? null,
                'location' => $entry['location'] ?? null,
                'jibble_data' => $entry,
                'synced_at' => now(),
            ]);

            $this->line("✓ Created time entry for {$employee->first_name} {$employee->last_name}");
            
            // Also create/update Attendance record
            $this->syncAttendance($employee, $clockInTime, $clockOutTime);
        } else {
            // Update existing entry
            $existingEntry->update([
                'clock_out_time' => $clockOutTime,
                'duration_minutes' => $durationMinutes,
                'jibble_data' => $entry,
                'synced_at' => now(),
            ]);

            $this->line("✓ Updated time entry for {$employee->first_name} {$employee->last_name}");
        }
    }

    /**
     * Create or update Attendance record based on time entry
     */
    private function syncAttendance(Employee $employee, Carbon $clockInTime, ?Carbon $clockOutTime): void
    {
        $date = $clockInTime->format('Y-m-d');

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $date)
            ->first();

        if (!$attendance) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date,
                'check_in_time' => $clockInTime,
                'check_out_time' => $clockOutTime,
                'status' => $clockOutTime ? 'present' : 'in_progress',
                'source' => 'jibble', // Track that this came from Jibble
            ]);
        } else {
            $attendance->update([
                'check_in_time' => $clockInTime,
                'check_out_time' => $clockOutTime,
                'status' => $clockOutTime ? 'present' : 'in_progress',
            ]);
        }
    }
}
