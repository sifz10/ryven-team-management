<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'hours_worked',
        'minutes_worked',
        'calculated_payment',
        'bonus',
        'penalty',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'bonus' => 'decimal:2',
        'penalty' => 'decimal:2',
        'calculated_payment' => 'decimal:2',
        'hours_worked' => 'integer',
        'minutes_worked' => 'integer',
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

    /**
     * Get total hours worked as decimal (e.g., 7.65 for 7 hours 39 minutes)
     */
    public function getTotalHoursAttribute(): float
    {
        $hours = (float)($this->hours_worked ?? 0);
        $minutes = (float)($this->minutes_worked ?? 0);
        return $hours + ($minutes / 60);
    }

    /**
     * Get final payment for the day (calculated_payment + bonus - penalty)
     */
    public function getFinalPaymentAttribute(): float
    {
        $payment = (float)($this->calculated_payment ?? 0);
        $bonus = (float)($this->bonus ?? 0);
        $penalty = (float)($this->penalty ?? 0);
        return max(0, $payment + $bonus - $penalty);
    }

    /**
     * Get formatted time string (e.g., "7h 39m")
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->hours_worked && !$this->minutes_worked) {
            return '';
        }
        $parts = [];
        if ($this->hours_worked) {
            $parts[] = $this->hours_worked . 'h';
        }
        if ($this->minutes_worked) {
            $parts[] = $this->minutes_worked . 'm';
        }
        return implode(' ', $parts);
    }
}

