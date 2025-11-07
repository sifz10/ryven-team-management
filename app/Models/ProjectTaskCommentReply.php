<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTaskCommentReply extends Model
{
    protected $fillable = [
        'comment_id',
        'employee_id',
        'reply',
    ];

    protected $appends = ['created_at_human'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(ProjectTaskComment::class, 'comment_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
