<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'github_username',
        'phone',
        'position',
        'department',
        'salary',
        'currency',
        'hired_at',
        'discontinued_at',
        'user_id',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'discontinued_at' => 'datetime',
    ];

    public function payments()
    {
        return $this->hasMany(EmployeePayment::class)->latest('paid_at');
    }

    public function bankAccounts()
    {
        return $this->hasMany(EmployeeBankAccount::class)->latest();
    }

    public function accesses()
    {
        return $this->hasMany(EmployeeAccess::class)->latest();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class)->latest('date');
    }

    public function monthlyAdjustments(): HasMany
    {
        return $this->hasMany(MonthlyAdjustment::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmploymentContract::class)->latest();
    }

    public function checklistTemplates(): HasMany
    {
        return $this->hasMany(ChecklistTemplate::class);
    }

    public function dailyChecklists(): HasMany
    {
        return $this->hasMany(DailyChecklist::class);
    }

    public function githubLogs(): HasMany
    {
        return $this->hasMany(GitHubLog::class)->latest('event_at');
    }

    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'employee_skills')
            ->withPivot(['proficiency_level', 'proficiency_label', 'years_experience', 'last_assessed_at', 'assessed_by', 'is_primary'])
            ->withTimestamps();
    }

    public function employeeSkills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class);
    }
}
