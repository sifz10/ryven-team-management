<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(ProjectTicket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    /**
     * Check if comment is from AI
     */
    public function isAI(): bool
    {
        return $this->user_id === null;
    }

    /**
     * Get author name (AI or employee)
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->isAI()) {
            return 'AI Assistant';
        }

        return $this->user ?
            $this->user->first_name . ' ' . $this->user->last_name :
            'Unknown User';
    }

    /**
     * Get author initials
     */
    public function getAuthorInitialsAttribute(): string
    {
        if ($this->isAI()) {
            return 'AI';
        }

        if ($this->user) {
            return substr($this->user->first_name, 0, 1) .
                   substr($this->user->last_name ?? '', 0, 1);
        }

        return '??';
    }
}
