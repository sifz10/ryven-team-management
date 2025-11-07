<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTaskComment extends Model
{
    protected $fillable = [
        'project_task_id',
        'employee_id',
        'comment',
    ];

    protected $with = ['employee', 'replies.employee', 'reactions.employee'];

    protected $appends = ['created_at_human'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProjectTaskCommentReply::class, 'comment_id')->orderBy('created_at', 'asc');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ProjectTaskCommentReaction::class, 'comment_id');
    }

    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
