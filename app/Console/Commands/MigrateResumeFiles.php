<?php

namespace App\Console\Commands;

use App\Models\JobApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateResumeFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resumes:migrate-to-local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate resume files from public storage to local storage for security';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting resume file migration...');

        $applications = JobApplication::whereNotNull('resume_path')->get();
        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($applications as $application) {
            $publicPath = $application->resume_path;

            // Check if file exists in public disk
            if (Storage::disk('public')->exists($publicPath)) {
                try {
                    // Read file from public disk
                    $fileContents = Storage::disk('public')->get($publicPath);

                    // Write to local disk
                    Storage::disk('local')->put($publicPath, $fileContents);

                    // Verify the file was written successfully
                    if (Storage::disk('local')->exists($publicPath)) {
                        // Delete from public disk
                        Storage::disk('public')->delete($publicPath);
                        $migrated++;
                        $this->info("✓ Migrated: {$publicPath}");
                    } else {
                        $errors++;
                        $this->error("✗ Failed to write to local: {$publicPath}");
                    }
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("✗ Error migrating {$publicPath}: " . $e->getMessage());
                }
            } elseif (Storage::disk('local')->exists($publicPath)) {
                // Already in local storage
                $skipped++;
                $this->line("- Already in local storage: {$publicPath}");
            } else {
                $errors++;
                $this->warn("⚠ File not found in either storage: {$publicPath}");
            }
        }

        $this->newLine();
        $this->info("Migration complete!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Migrated', $migrated],
                ['Skipped (already in local)', $skipped],
                ['Errors', $errors],
                ['Total applications', $applications->count()],
            ]
        );

        return 0;
    }
}
