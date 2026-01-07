<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryReview extends Model
{
    protected $fillable = [
        'employee_id',
        'review_date',
        'status',
        'previous_salary',
        'adjusted_salary',
        'adjustment_amount',
        'performance_notes',
        'performance_rating',
        'adjustment_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'review_date' => 'date',
        'reviewed_at' => 'datetime',
        'previous_salary' => 'decimal:2',
        'adjusted_salary' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function adjustmentHistory(): HasMany
    {
        return $this->hasMany(SalaryAdjustmentHistory::class);
    }

    /**
     * Mark review as completed with salary adjustment
     */
    public function completeReview(float $newSalary, string $reason, int $reviewedBy): void
    {
        $this->update([
            'status' => 'completed',
            'adjusted_salary' => $newSalary,
            'adjustment_amount' => $newSalary - $this->previous_salary,
            'adjustment_reason' => $reason,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
        ]);

        // Create adjustment history record
        SalaryAdjustmentHistory::create([
            'employee_id' => $this->employee_id,
            'salary_review_id' => $this->id,
            'old_salary' => $this->previous_salary,
            'new_salary' => $newSalary,
            'adjustment_amount' => $newSalary - $this->previous_salary,
            'type' => 'review',
            'reason' => $reason,
            'adjusted_by' => $reviewedBy,
            'currency' => $this->employee->currency ?? 'USD',
        ]);

        // Update employee's salary
        $this->employee->update(['salary' => $newSalary]);
    }

    /**
     * Check if review is due (within 5 days before review_date)
     */
    public function isDueForReminder(): bool
    {
        return $this->status === 'pending' && 
               $this->review_date->diffInDays(now()) <= 5 &&
               $this->review_date->isFuture();
    }
}
