<?php

namespace App\Console\Commands;

use App\Models\ClientTeamMember;
use App\Models\Client;
use Illuminate\Console\Command;

class CleanOrphanedTeamMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:clean-orphaned-team-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove team member records that have no associated Client account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for orphaned team member records...');

        // Find all team members
        $teamMembers = ClientTeamMember::all();
        $orphanedCount = 0;
        $orphanedRecords = [];

        foreach ($teamMembers as $teamMember) {
            // Check if the associated Client exists
            if (!Client::find($teamMember->team_member_client_id)) {
                $orphanedRecords[] = [
                    'ID' => $teamMember->id,
                    'Name' => $teamMember->name,
                    'Email' => $teamMember->email,
                    'Missing Client ID' => $teamMember->team_member_client_id,
                ];
                $orphanedCount++;
            }
        }

        if ($orphanedCount === 0) {
            $this->info('✓ No orphaned team member records found. All team members have valid Client accounts.');
            return Command::SUCCESS;
        }

        // Display orphaned records
        $this->warn("Found {$orphanedCount} orphaned team member record(s):");
        $this->table(
            ['ID', 'Name', 'Email', 'Missing Client ID'],
            $orphanedRecords
        );

        // Ask for confirmation
        if ($this->confirm('Do you want to delete these orphaned records?', true)) {
            foreach ($teamMembers as $teamMember) {
                if (!Client::find($teamMember->team_member_client_id)) {
                    $teamMember->delete();
                }
            }

            $this->info("✓ Successfully deleted {$orphanedCount} orphaned team member record(s).");
        } else {
            $this->info('Cleanup cancelled.');
        }

        return Command::SUCCESS;
    }
}
