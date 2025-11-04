<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PersonalNote extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'url',
        'file_path',
        'reminder_time',
        'reminder_sent',
    ];

    protected $casts = [
        'reminder_time' => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(NoteRecipient::class);
    }

    public function scopePendingReminders($query)
    {
        return $query->where('reminder_sent', false)
            ->whereNotNull('reminder_time')
            ->where('reminder_time', '<=', now());
    }
}
