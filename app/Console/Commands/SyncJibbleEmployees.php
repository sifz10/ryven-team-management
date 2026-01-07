<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\JibbleSyncLog;
use App\Services\JibbleApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncJibbleEmployees extends Command
{
    protected $signature = 'jibble:sync-employees {--force : Force sync even if recently synced}';
    protected $description = 'Sync employees from Jibble API';

    public function handle()
    {
        if (!config('jibble.enabled')) {
            $this->error('Jibble integration is not enabled in configuration');
            return 1;
        }

        $syncLog = JibbleSyncLog::create([
            'sync_type' => 'employees',
            'status' => 'processing',
            'started_at' => now(),
        ]);

        try {
            $this->info('Starting employee sync from Jibble...');
            
            $jibbleService = new JibbleApiService();
            $jibbleMembers = $jibbleService->getMembers();

            if (empty($jibbleMembers)) {
                $this->warn('No members found from Jibble API');
                $syncLog->update([
                    'status' => 'completed',
                    'records_synced' => 0,
                    'completed_at' => now(),
                ]);
                return 0;
            }

            $synced = 0;
            $failed = 0;

            foreach ($jibbleMembers as $member) {
                try {
                    $this->syncEmployee($member);
                    $synced++;
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Failed to sync Jibble member: {$member['id']}", [
                        'error' => $e->getMessage(),
                        'member' => $member,
                    ]);
                }
            }

            $syncLog->update([
                'status' => 'completed',
                'records_synced' => $synced,
                'records_failed' => $failed,
                'completed_at' => now(),
            ]);

            $this->info("✅ Sync completed! Synced: {$synced}, Failed: {$failed}");
            return 0;

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::error('Jibble employee sync failed: ' . $errorMsg);
            
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
     * Sync a single employee from Jibble member data
     */
    private function syncEmployee(array $member): void
    {
        $jibbleId = $member['id'];
        $email = $member['email'] ?? null;
        
        // Try to find existing employee by Jibble ID
        $employee = Employee::where('jibble_id', $jibbleId)->first();

        // If not found, try to find by email
        if (!$employee && $email) {
            $employee = Employee::where('email', $email)->first();
        }

        if (!$employee) {
            // Create new employee from Jibble data
            $employee = Employee::create([
                'first_name' => $member['firstName'] ?? 'Unknown',
                'last_name' => $member['lastName'] ?? '',
                'email' => $email,
                'jibble_id' => $jibbleId,
                'jibble_email' => $email,
                'jibble_data' => $member,
                'jibble_synced_at' => now(),
                'is_active' => $member['isActive'] ?? true,
            ]);

            $this->line("✓ Created employee: {$employee->first_name} {$employee->last_name}");
        } else {
            // Update existing employee
            $employee->update([
                'jibble_id' => $jibbleId,
                'jibble_email' => $email,
                'jibble_data' => $member,
                'jibble_synced_at' => now(),
                'is_active' => $member['isActive'] ?? true,
            ]);

            $this->line("✓ Updated employee: {$employee->first_name} {$employee->last_name}");
        }
    }
}
