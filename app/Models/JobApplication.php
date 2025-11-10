<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_post_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'linkedin_url',
        'portfolio_url',
        'cover_letter',
        'resume_path',
        'resume_original_name',
        'resume_text',
        'ai_status',
        'ai_match_score',
        'ai_analysis',
        'application_status',
        'admin_notes',
        'added_to_talent_pool',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'ai_analysis' => 'array',
        'added_to_talent_pool' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function answers()
    {
        return $this->hasMany(ApplicationAnswer::class);
    }

    public function tests()
    {
        return $this->hasMany(ApplicationTest::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }

    public function talentPoolEntry()
    {
        return $this->hasOne(TalentPool::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeBestMatch($query)
    {
        return $query->where('ai_status', 'best_match');
    }

    public function scopeGoodToGo($query)
    {
        return $query->where('ai_status', 'good_to_go');
    }

    public function scopeNotGoodFit($query)
    {
        return $query->where('ai_status', 'not_good_fit');
    }

    public function scopePending($query)
    {
        return $query->where('ai_status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('application_status', $status);
    }
}
