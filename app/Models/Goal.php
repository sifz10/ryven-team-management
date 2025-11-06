<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    protected $fillable = [
        'employee_id',
        'review_cycle_id',
        'title',
        'description',
        'type',
        'category',
        'weight',
        'start_date',
        'due_date',
        'completed_at',
        'status',
        'progress',
        'key_results',
        'metrics',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'key_results' => 'array',
        'metrics' => 'array',
        'weight' => 'integer',
        'progress' => 'integer',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['not_started', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOKRs($query)
    {
        return $query->where('type', 'okr');
    }

    public function scopeKPIs($query)
    {
        return $query->where('type', 'kpi');
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->due_date < now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getProgressPercentage(): int
    {
        return $this->progress ?? 0;
    }

    public function updateProgress(int $progress): void
    {
        $this->progress = min(100, max(0, $progress));
        
        if ($this->progress === 100 && $this->status !== 'completed') {
            $this->status = 'completed';
            $this->completed_at = now();
        } elseif ($this->progress > 0 && $this->status === 'not_started') {
            $this->status = 'in_progress';
        }
        
        $this->save();
    }
}
