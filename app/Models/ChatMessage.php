<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_conversation_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment_path',
        'attachment_name',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'chat_conversation_id');
    }

    public function sender(): BelongsTo
    {
        if ($this->sender_type === 'employee') {
            return $this->belongsTo(Employee::class, 'sender_id');
        }
        return $this->belongsTo(ChatbotWidget::class, 'sender_id');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}
