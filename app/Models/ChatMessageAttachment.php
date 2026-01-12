<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessageAttachment extends Model
{
    protected $table = 'chat_message_attachments';

    protected $fillable = [
        'chat_message_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    /**
     * Get the public URL for the attachment
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Format attachment as array for API response
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->file_name,
            'type' => $this->file_type,
            'size' => $this->file_size,
            'url' => $this->getUrlAttribute(),
        ];
    }
}
