<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    protected $fillable = [
        'employee_id',
        'paid_at',
        'activity_type',
        'amount',
        'currency',
        'note',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
