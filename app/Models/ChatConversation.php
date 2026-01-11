<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    protected $fillable = [
        'chatbot_widget_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visitor_ip',
        'visitor_metadata',
        'assigned_to_employee_id',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'visitor_metadata' => 'array',
        'last_message_at' => 'datetime',
    ];

    public function chatbotWidget(): BelongsTo
    {
        return $this->belongsTo(ChatbotWidget::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to_employee_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['active', 'pending']);
    }
}
