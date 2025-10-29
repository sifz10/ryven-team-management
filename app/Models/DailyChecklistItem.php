<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyChecklistItem extends Model
{
    protected $fillable = [
        'daily_checklist_id',
        'checklist_template_item_id',
        'title',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function dailyChecklist(): BelongsTo
    {
        return $this->belongsTo(DailyChecklist::class);
    }

    public function templateItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplateItem::class, 'checklist_template_item_id');
    }
}
