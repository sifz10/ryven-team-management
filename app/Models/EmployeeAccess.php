<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAccess extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'note_markdown',
        'attachment_path',
        'attachment_name',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
