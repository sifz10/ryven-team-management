<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailMessage extends Model
{
    protected $fillable = [
        'email_account_id',
        'message_id',
        'in_reply_to',
        'references',
        'folder',
        'from_email',
        'from_name',
        'to',
        'cc',
        'bcc',
        'subject',
        'body_html',
        'body_text',
        'is_read',
        'is_starred',
        'is_draft',
        'has_attachments',
        'direction',
        'sent_at',
    ];

    protected $casts = [
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_draft' => 'boolean',
        'has_attachments' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }

    // Alias for account relationship
    public function emailAccount(): BelongsTo
    {
        return $this->account();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    public function toggleStar(): void
    {
        $this->update(['is_starred' => !$this->is_starred]);
    }

    /**
     * Get all messages in this conversation thread
     */
    public function thread()
    {
        if (!$this->message_id) {
            return collect([$this]);
        }

        // Find all messages with the same message_id in references or in_reply_to
        return self::where('email_account_id', $this->email_account_id)
            ->where(function($query) {
                $query->where('message_id', $this->message_id)
                    ->orWhere('in_reply_to', $this->message_id)
                    ->orWhere('references', 'like', '%' . $this->message_id . '%');
                    
                if ($this->in_reply_to) {
                    $query->orWhere('message_id', $this->in_reply_to)
                        ->orWhere('in_reply_to', $this->in_reply_to);
                }
            })
            ->orderBy('sent_at', 'asc')
            ->get();
    }

    /**
     * Check if this is part of a conversation
     */
    public function isPartOfConversation(): bool
    {
        return !empty($this->in_reply_to) || !empty($this->references);
    }

    /**
     * Get the original message in the thread
     */
    public function originalMessage()
    {
        if (!$this->in_reply_to) {
            return $this;
        }

        return self::where('email_account_id', $this->email_account_id)
            ->where('message_id', $this->in_reply_to)
            ->first() ?? $this;
    }
}
