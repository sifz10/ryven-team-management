<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UatTestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'uat_project_id',
        'title',
        'description',
        'steps',
        'expected_result',
        'priority',
        'order',
        'created_by',
    ];

    public function project()
    {
        return $this->belongsTo(UatProject::class, 'uat_project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function feedbacks()
    {
        return $this->hasMany(UatFeedback::class);
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'gray',
            default => 'gray',
        };
    }
}

