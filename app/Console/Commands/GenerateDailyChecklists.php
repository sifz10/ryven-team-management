<?php

namespace App\Console\Commands;

use App\Models\ChecklistTemplate;
use App\Models\DailyChecklist;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateDailyChecklists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checklists:generate-daily {--date= : The date to generate checklists for (Y-m-d format, defaults to today)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily checklists for all employees with active checklist templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();
        
        $this->info("Generating daily checklists for {$date}...");
        
        // Get all active checklist templates
        $templates = ChecklistTemplate::where('is_active', true)
            ->with(['employee', 'items'])
            ->get();
        
        if ($templates->isEmpty()) {
            $this->warn('No active checklist templates found.');
            return Command::SUCCESS;
        }
        
        $generatedCount = 0;
        $skippedCount = 0;
        
        foreach ($templates as $template) {
            // Check if the employee is active (not discontinued)
            if ($template->employee->discontinued_at) {
                $this->line("Skipping discontinued employee: {$template->employee->first_name} {$template->employee->last_name}");
                continue;
            }
            
            // Check if checklist already exists for this date
            $existingChecklist = DailyChecklist::where('employee_id', $template->employee_id)
                ->where('checklist_template_id', $template->id)
                ->where('date', $date)
                ->first();
            
            if ($existingChecklist) {
                $skippedCount++;
                continue;
            }
            
            // Generate the daily checklist
            DB::beginTransaction();
            try {
                $dailyChecklist = DailyChecklist::create([
                    'employee_id' => $template->employee_id,
                    'checklist_template_id' => $template->id,
                    'date' => $date,
                ]);
                
                // Create checklist items from template
                foreach ($template->items as $templateItem) {
                    $dailyChecklist->items()->create([
                        'checklist_template_item_id' => $templateItem->id,
                        'title' => $templateItem->title,
                        'is_completed' => false,
                    ]);
                }
                
                DB::commit();
                $generatedCount++;
                $this->info("✓ Generated checklist for {$template->employee->first_name} {$template->employee->last_name} - {$template->title}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("✗ Failed to generate checklist for {$template->employee->first_name} {$template->employee->last_name}: {$e->getMessage()}");
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Generated: {$generatedCount} checklist(s)");
        $this->info("- Skipped (already exists): {$skippedCount} checklist(s)");
        $this->info("- Total templates processed: {$templates->count()}");
        
        return Command::SUCCESS;
    }
}
