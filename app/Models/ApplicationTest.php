<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'test_title',
        'test_description',
        'test_instructions',
        'test_file_path',
        'submission_file_path',
        'submission_original_name',
        'status',
        'sent_at',
        'submitted_at',
        'deadline',
        'score',
        'feedback',
        'sent_by',
        'reviewed_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'submitted_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'sent_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }

    public function isOverdue()
    {
        return $this->deadline && $this->deadline < now() && $this->status === 'sent';
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'sent');
    }
}
