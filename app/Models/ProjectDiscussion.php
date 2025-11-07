<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectDiscussion extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'parent_id',
        'message',
        'mentions',
        'attachments',
        'is_pinned',
    ];

    protected $casts = [
        'mentions' => 'array',
        'attachments' => 'array',
        'is_pinned' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectDiscussion::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProjectDiscussion::class, 'parent_id');
    }
}
