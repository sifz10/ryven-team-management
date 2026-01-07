<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\SalaryReview;
use Illuminate\Console\Command;

class CreateSalaryReviews extends Command
{
    protected $signature = 'salary-reviews:create';

    protected $description = 'Create salary reviews for employees 6 months after hire date';

    public function handle(): int
    {
        $employees = Employee::whereNotNull('hired_at')
            ->whereNull('discontinued_at')
            ->get();

        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Check if review already exists
            $reviewDate = $employee->hired_at->addMonths(6);
            
            $existing = SalaryReview::where('employee_id', $employee->id)
                ->where('review_date', $reviewDate->toDateString())
                ->exists();

            if ($existing) {
                $skipped++;
                continue;
            }

            // Create salary review if hire date was at least 6 months ago
            if ($reviewDate->isPast() || $reviewDate->isFuture()) {
                SalaryReview::create([
                    'employee_id' => $employee->id,
                    'review_date' => $reviewDate,
                    'status' => $reviewDate->isPast() ? 'pending' : 'pending',
                    'previous_salary' => $employee->salary,
                ]);
                $created++;
            }
        }

        $this->info("Created {$created} salary reviews. Skipped {$skipped} (already exist).");
        return 0;
    }
}
