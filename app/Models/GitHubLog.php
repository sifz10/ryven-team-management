<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GitHubLog extends Model
{
    protected $table = 'github_logs';
    
    protected $fillable = [
        'employee_id',
        'event_type',
        'action',
        'repository_name',
        'repository_url',
        'branch',
        'ref',
        'commit_message',
        'commit_sha',
        'commit_url',
        'commits_count',
        'pr_number',
        'pr_title',
        'pr_description',
        'pr_url',
        'pr_state',
        'pr_merged',
        'author_username',
        'author_avatar_url',
        'payload',
        'event_at',
    ];

    protected $casts = [
        'event_at' => 'datetime',
        'pr_merged' => 'boolean',
        'payload' => 'array',
        'commits_count' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get a formatted display name for the event type
     */
    public function getEventDisplayNameAttribute(): string
    {
        return match($this->event_type) {
            'push' => 'Push',
            'pull_request' => 'Pull Request',
            'pull_request_review' => 'PR Review',
            'pull_request_review_comment' => 'PR Comment',
            'issues' => 'Issue',
            'issue_comment' => 'Issue Comment',
            'create' => 'Branch/Tag Created',
            'delete' => 'Branch/Tag Deleted',
            default => ucfirst(str_replace('_', ' ', $this->event_type)),
        };
    }

    /**
     * Get icon emoji for event type
     */
    public function getEventIconAttribute(): string
    {
        return match($this->event_type) {
            'push' => '📤',
            'pull_request' => '🔀',
            'pull_request_review' => '👀',
            'pull_request_review_comment' => '💬',
            'issues' => '🐛',
            'issue_comment' => '💬',
            'create' => '🌱',
            'delete' => '🗑️',
            default => '📋',
        };
    }
}
