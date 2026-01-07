<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JibbleSyncLog extends Model
{
    protected $fillable = [
        'sync_type',
        'status',
        'records_synced',
        'records_failed',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Get latest sync for a type
     */
    public function scopeLatestByType($query, string $type)
    {
        return $query->where('sync_type', $type)->latest()->first();
    }

    /**
     * Scope: Get recent syncs
     */
    public function scopeRecent($query)
    {
        return $query->latest()->limit(50);
    }
}
