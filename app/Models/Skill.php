<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function employeeSkills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_skills')
            ->withPivot(['proficiency_level', 'proficiency_label', 'years_experience', 'last_assessed_at', 'assessed_by', 'is_primary'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeTechnical($query)
    {
        return $query->where('category', 'technical');
    }

    public function scopeSoft($query)
    {
        return $query->where('category', 'soft');
    }

    public function scopeLeadership($query)
    {
        return $query->where('category', 'leadership');
    }

    public function scopeDomain($query)
    {
        return $query->where('category', 'domain');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
