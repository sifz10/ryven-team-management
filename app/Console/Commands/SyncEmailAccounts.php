<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use App\Services\EmailFetchService;
use Illuminate\Console\Command;

class SyncEmailAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sync {--account= : Specific account ID to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all active email accounts or a specific account';

    /**
     * Execute the console command.
     */
    public function handle(EmailFetchService $fetchService)
    {
        $accountId = $this->option('account');

        if ($accountId) {
            $accounts = EmailAccount::where('id', $accountId)->where('is_active', true)->get();
        } else {
            $accounts = EmailAccount::where('is_active', true)->get();
        }

        if ($accounts->isEmpty()) {
            $this->error('No active email accounts found.');
            return 1;
        }

        $this->info("Syncing " . $accounts->count() . " account(s)...");

        foreach ($accounts as $account) {
            try {
                $this->line("Syncing {$account->name} ({$account->email})...");
                
                $newEmailsCount = $fetchService->fetchEmails($account, 20);
                
                $this->info("✓ Found {$newEmailsCount} new emails");
                
                $account->update(['last_synced_at' => now()]);
            } catch (\Exception $e) {
                $this->error("✗ Failed: " . $e->getMessage());
            }
        }

        $this->info('Sync completed!');
        return 0;
    }
}
