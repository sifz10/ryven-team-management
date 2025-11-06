<?php

namespace App\Jobs;

use App\Models\EmailAccount;
use App\Services\EmailFetchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchEmails implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(EmailFetchService $fetchService): void
    {
        $accounts = EmailAccount::where('is_active', true)->get();

        foreach ($accounts as $account) {
            try {
                $newEmailsCount = $fetchService->fetchEmails($account, 20);
                
                if ($newEmailsCount > 0) {
                    Log::info("Fetched {$newEmailsCount} new emails for account: {$account->email}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch emails for account {$account->email}: " . $e->getMessage());
            }
        }
    }
}
