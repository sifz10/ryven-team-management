<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryAdjustmentHistory extends Model
{
    protected $table = 'salary_adjustment_history';

    protected $fillable = [
        'employee_id',
        'salary_review_id',
        'old_salary',
        'new_salary',
        'adjustment_amount',
        'type',
        'reason',
        'adjusted_by',
        'currency',
    ];

    protected $casts = [
        'old_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryReview(): BelongsTo
    {
        return $this->belongsTo(SalaryReview::class);
    }

    public function adjustedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}
