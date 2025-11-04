<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPost extends Model
{
    protected $fillable = [
        'user_id',
        'social_account_id',
        'title',
        'description',
        'content',
        'final_content',
        'status',
        'scheduled_at',
        'posted_at',
        'platform_post_id',
        'platform_response',
        'error_message',
        'auto_generate',
        'retry_count',
    ];

    protected $casts = [
        'platform_response' => 'array',
        'scheduled_at' => 'datetime',
        'posted_at' => 'datetime',
        'auto_generate' => 'boolean',
        'retry_count' => 'integer',
    ];

    /**
     * Get the user that owns the post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the social account for this post
     */
    public function socialAccount(): BelongsTo
    {
        return $this->belongsTo(SocialAccount::class);
    }

    /**
     * Get all AI-generated versions for this post
     */
    public function generations(): HasMany
    {
        return $this->hasMany(PostGeneration::class);
    }

    /**
     * Get the selected AI-generated version
     */
    public function selectedGeneration()
    {
        return $this->hasOne(PostGeneration::class)->where('is_selected', true);
    }

    /**
     * Scope for posts ready to be published
     */
    public function scopeReadyToPost($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->whereNotNull('social_account_id');
    }

    /**
     * Scope for posts by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if post is ready to publish
     */
    public function isReadyToPost(): bool
    {
        return $this->status === 'scheduled' 
            && $this->scheduled_at 
            && $this->scheduled_at->isPast()
            && $this->social_account_id;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'scheduled' => 'blue',
            'posted' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }
}
