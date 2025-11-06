<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailAccount extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'protocol',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'is_default',
        'is_active',
        'last_synced_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'last_synced_at' => 'datetime',
        'imap_password' => 'encrypted',
        'smtp_password' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(EmailMessage::class)->where('is_read', false);
    }
}
