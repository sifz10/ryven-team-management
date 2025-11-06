<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id',
        'review_cycle_id',
        'template_id',
        'status',
        'overall_rating',
        'ratings',
        'strengths',
        'areas_for_improvement',
        'achievements',
        'manager_comments',
        'employee_comments',
        'requires_pip',
        'pip_created',
        'reviewed_at',
        'submitted_at',
        'reviewer_id',
    ];

    protected $casts = [
        'ratings' => 'array',
        'requires_pip' => 'boolean',
        'pip_created' => 'boolean',
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'overall_rating' => 'decimal:2',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewCycle(): BelongsTo
    {
        return $this->belongsTo(ReviewCycle::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReviewTemplate::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(ReviewFeedback::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRequiresPIP($query)
    {
        return $query->where('requires_pip', true);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'in_progress']);
    }
}
