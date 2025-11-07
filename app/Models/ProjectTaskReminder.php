<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_task_id',
        'created_by',
        'recipient_type',
        'recipient_id',
        'remind_at',
        'message',
        'is_sent',
        'sent_at',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    /**
     * Get the task that owns the reminder
     */
    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    /**
     * Get the employee who created the reminder
     */
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    /**
     * Get the recipient (polymorphic)
     */
    public function recipient()
    {
        if ($this->recipient_type === 'employee') {
            return Employee::find($this->recipient_id);
        } elseif ($this->recipient_type === 'client') {
            return \App\Models\UatUser::find($this->recipient_id);
        }
        return null;
    }

    /**
     * Scope for pending reminders
     */
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
                    ->where('remind_at', '<=', now());
    }

    /**
     * Scope for upcoming reminders
     */
    public function scopeUpcoming($query)
    {
        return $query->where('is_sent', false)
                    ->where('remind_at', '>', now());
    }
}
