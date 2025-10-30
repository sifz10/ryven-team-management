<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityNote extends Model
{
    protected $fillable = [
        'employee_payment_id',
        'user_id',
        'note',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(EmployeePayment::class, 'employee_payment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
