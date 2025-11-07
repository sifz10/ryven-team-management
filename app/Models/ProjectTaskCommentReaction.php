<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTaskCommentReaction extends Model
{
    protected $fillable = [
        'comment_id',
        'employee_id',
        'reaction_type',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(ProjectTaskComment::class, 'comment_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function getReactionEmoji(string $type): string
    {
        return match($type) {
            'like' => '👍',
            'love' => '❤️',
            'laugh' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😡',
            default => '👍',
        };
    }
}
