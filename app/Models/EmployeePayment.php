<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ActivityNote::class, 'employee_payment_id')->latest();
    }
}
