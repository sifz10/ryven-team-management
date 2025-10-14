<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'salary',
        'currency',
        'hired_at',
        'discontinued_at',
        'user_id',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'discontinued_at' => 'datetime',
    ];

    public function payments()
    {
        return $this->hasMany(EmployeePayment::class)->latest('paid_at');
    }

    public function bankAccounts()
    {
        return $this->hasMany(EmployeeBankAccount::class)->latest();
    }

    public function accesses()
    {
        return $this->hasMany(EmployeeAccess::class)->latest();
    }
}
