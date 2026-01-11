<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChatbotWidget extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'api_token',
        'installed_in',
        'welcome_message',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->api_token) {
                $model->api_token = 'cbw_' . Str::random(60);
            }
        });
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function generateNewToken()
    {
        $this->api_token = 'cbw_' . Str::random(60);
        $this->save();
        return $this->api_token;
    }
}
