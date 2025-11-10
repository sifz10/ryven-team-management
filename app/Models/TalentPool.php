<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalentPool extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'talent_pool';

    protected $fillable = [
        'job_application_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'linkedin_url',
        'portfolio_url',
        'skills',
        'experience_level',
        'resume_path',
        'notes',
        'status',
        'source',
        'last_contacted_at',
        'added_by',
    ];

    protected $casts = [
        'skills' => 'array',
        'last_contacted_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Employee::class, 'added_by');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopePotential($query)
    {
        return $query->where('status', 'potential');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeInterested($query)
    {
        return $query->where('status', 'interested');
    }
}
