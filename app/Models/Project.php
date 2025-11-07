<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'status',
        'progress',
        'start_date',
        'end_date',
        'client_name',
        'client_email',
        'client_phone',
        'client_company',
        'client_address',
        'project_manager',
        'budget',
        'currency',
        'priority',
        'last_report_sent_at',
        'auto_send_reports',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'priority' => 'integer',
        'progress' => 'integer',
        'last_report_sent_at' => 'datetime',
        'auto_send_reports' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function workSubmissions(): HasMany
    {
        return $this->hasMany(DailyWorkSubmission::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ProjectDiscussion::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(ProjectTicket::class);
    }

    // Helper method to get priority label
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Critical',
            default => 'Medium'
        };
    }

    // Helper method to get priority color
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            1 => 'gray',
            2 => 'blue',
            3 => 'orange',
            4 => 'red',
            default => 'blue'
        };
    }

    // Helper method to get status badge color
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'completed' => 'blue',
            'on-hold' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
