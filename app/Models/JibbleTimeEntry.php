<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JibbleTimeEntry extends Model
{
    protected $fillable = [
        'employee_id',
        'jibble_entry_id',
        'clock_in_time',
        'clock_out_time',
        'duration_minutes',
        'notes',
        'location',
        'jibble_data',
        'synced_at',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'synced_at' => 'datetime',
        'jibble_data' => 'array',
    ];

    /**
     * Relationship with Employee
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get hours worked
     */
    public function getHoursWorkedAttribute(): float
    {
        if (!$this->duration_minutes) {
            return 0;
        }
        return round($this->duration_minutes / 60, 2);
    }

    /**
     * Scope: Get entries for a date range
     */
    public function scopeForDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('clock_in_time', [$startDate, $endDate]);
    }

    /**
     * Scope: Get entries for an employee in date range
     */
    public function scopeForEmployeeInRange($query, int $employeeId, string $startDate, string $endDate)
    {
        return $query->where('employee_id', $employeeId)
            ->whereBetween('clock_in_time', [$startDate, $endDate]);
    }
}
