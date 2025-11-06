<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSkill extends Model
{
    protected $fillable = [
        'employee_id',
        'skill_id',
        'proficiency_level',
        'proficiency_label',
        'years_experience',
        'last_assessed_at',
        'assessed_by',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'proficiency_level' => 'integer',
        'years_experience' => 'decimal:1',
        'last_assessed_at' => 'datetime',
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByProficiency($query, $level)
    {
        return $query->where('proficiency_level', $level);
    }

    public function scopeExpert($query)
    {
        return $query->whereIn('proficiency_level', [4, 5]);
    }

    public function scopeBeginner($query)
    {
        return $query->where('proficiency_level', 1);
    }

    // Helper methods
    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    public function getProficiencyLabelAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return match($this->proficiency_level) {
            1 => 'Beginner',
            2 => 'Intermediate',
            3 => 'Advanced',
            4 => 'Expert',
            5 => 'Master',
            default => 'Unknown',
        };
    }

    public function isExpert(): bool
    {
        return $this->proficiency_level >= 4;
    }
}
