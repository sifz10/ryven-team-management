<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyAdjustment extends Model
{
    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'bonus',
        'penalty',
        'bonus_description',
        'penalty_description',
    ];

    protected $casts = [
        'bonus' => 'decimal:2',
        'penalty' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get net adjustment (bonus - penalty)
     */
    public function getNetAdjustmentAttribute(): float
    {
        return (float)($this->bonus - $this->penalty);
    }
}

