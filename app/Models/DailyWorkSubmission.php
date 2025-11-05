<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkSubmission extends Model
{
    protected $fillable = [
        'daily_checklist_id',
        'employee_id',
        'project_id',
        'project_name',
        'work_description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dailyChecklist(): BelongsTo
    {
        return $this->belongsTo(DailyChecklist::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Get project name (either from relationship or fallback to project_name field)
    public function getProjectNameAttribute(): string
    {
        if ($this->project_id && $this->project) {
            return $this->project->name;
        }
        return $this->attributes['project_name'] ?? 'N/A';
    }
}
