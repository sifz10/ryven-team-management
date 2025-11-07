<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectTask extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'order',
    ];

    protected $casts = [
        'due_date' => 'date',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
        'order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(ProjectTaskChecklist::class)->orderBy('order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProjectTaskTag::class, 'project_task_tag', 'project_task_id', 'project_task_tag_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectTaskFile::class)->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ProjectTaskComment::class)->latest();
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(ProjectTaskReminder::class)->latest();
    }

    // Relationship alias for easier access
    public function assignee()
    {
        return $this->assignedTo();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'todo' => 'gray',
            'in-progress' => 'blue',
            'in-review' => 'yellow',
            'completed' => 'green',
            'blocked' => 'red',
            default => 'gray'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'critical' => 'red',
            default => 'blue'
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
            default => ucfirst($this->priority)
        };
    }
}
