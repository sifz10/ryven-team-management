<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankAccount extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'details_markdown',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
