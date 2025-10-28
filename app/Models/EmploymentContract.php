<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentContract extends Model
{
    protected $fillable = [
        'employee_id',
        'contract_type',
        'start_date',
        'end_date',
        'job_title',
        'department',
        'job_description',
        'salary',
        'currency',
        'payment_frequency',
        'working_hours_per_week',
        'work_location',
        'work_schedule',
        'probation_period_days',
        'notice_period_days',
        'benefits',
        'annual_leave_days',
        'sick_leave_days',
        'responsibilities',
        'additional_terms',
        'status',
        'signed_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
