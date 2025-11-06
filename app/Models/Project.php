<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'client_name',
        'client_email',
        'client_phone',
        'client_company',
        'client_address',
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
        'last_report_sent_at' => 'datetime',
        'auto_send_reports' => 'boolean',
    ];

    public function workSubmissions(): HasMany
    {
        return $this->hasMany(DailyWorkSubmission::class);
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
