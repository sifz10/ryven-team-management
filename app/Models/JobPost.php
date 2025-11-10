<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'job_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'requirements',
        'responsibilities',
        'benefits',
        'status',
        'application_deadline',
        'contact_email',
        'department',
        'positions_available',
        'ai_screening_enabled',
        'ai_screening_criteria',
        'created_by',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'date',
        'ai_screening_enabled' => 'boolean',
        // ai_screening_criteria left as text - can be string or JSON
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobPost) {
            if (empty($jobPost->slug)) {
                $jobPost->slug = Str::slug($jobPost->title) . '-' . Str::random(6);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(JobQuestion::class)->orderBy('order');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function getPublicUrlAttribute()
    {
        return route('jobs.show', $this->slug);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                    ->orWhere('application_deadline', '>=', now());
            });
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['published', 'draft']);
    }

    public function isExpired()
    {
        return $this->application_deadline && $this->application_deadline < now();
    }

    public function canAcceptApplications()
    {
        return $this->status === 'published' && !$this->isExpired();
    }
}
