<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewFeedback extends Model
{
    protected $fillable = [
        'performance_review_id',
        'reviewer_id',
        'feedback_type',
        'rating',
        'ratings',
        'comments',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'ratings' => 'array',
        'rating' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function performanceReview(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('feedback_type', $type);
    }

    public function scopeSelfAssessment($query)
    {
        return $query->where('feedback_type', 'self');
    }

    public function scopeManagerFeedback($query)
    {
        return $query->where('feedback_type', 'manager');
    }

    public function scopePeerFeedback($query)
    {
        return $query->where('feedback_type', 'peer');
    }

    // Helper methods
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function canEdit(): bool
    {
        return $this->status === 'pending';
    }
}
