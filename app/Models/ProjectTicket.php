<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTicket extends Model
{
    protected $fillable = [
        'project_id',
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        'type',
        'reported_by',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reported_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'red',
            'in-progress' => 'blue',
            'resolved' => 'green',
            'closed' => 'gray',
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

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'bug' => 'red',
            'feature' => 'green',
            'enhancement' => 'blue',
            'question' => 'yellow',
            default => 'gray'
        };
    }
}
