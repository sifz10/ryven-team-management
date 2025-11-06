<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sections',
        'rating_scale',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'sections' => 'array',
        'rating_scale' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class, 'template_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }

    public function getSections(): array
    {
        return $this->sections ?? [];
    }

    public function getRatingScale(): array
    {
        return $this->rating_scale ?? [
            ['value' => 1, 'label' => 'Needs Improvement'],
            ['value' => 2, 'label' => 'Meets Expectations'],
            ['value' => 3, 'label' => 'Exceeds Expectations'],
            ['value' => 4, 'label' => 'Outstanding'],
        ];
    }
}
