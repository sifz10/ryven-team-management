<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyChecklist extends Model
{
    protected $fillable = [
        'employee_id',
        'checklist_template_id',
        'date',
        'email_token',
        'email_sent_at',
    ];

    protected $casts = [
        'date' => 'date',
        'email_sent_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DailyChecklistItem::class);
    }

    public function getCompletionPercentageAttribute(): float
    {
        $total = $this->items()->count();
        if ($total === 0) return 0;
        
        $completed = $this->items()->where('is_completed', true)->count();
        return round(($completed / $total) * 100, 1);
    }
}
