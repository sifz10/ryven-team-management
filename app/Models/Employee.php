<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'github_username',
        'phone',
        'position',
        'department',
        'salary',
        'currency',
        'hired_at',
        'discontinued_at',
        'is_active',
        'user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'discontinued_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name of the employee
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if employee can login
     */
    public function canLogin(): bool
    {
        return $this->is_active && !$this->discontinued_at && !empty($this->password);
    }

    /**
     * The roles that belong to the employee.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'employee_role')
            ->withTimestamps();
    }

    /**
     * Get all permissions for the employee through their roles.
     */
    public function permissions()
    {
        return $this->roles->flatMap->permissions->unique('id');
    }

    /**
     * Check if employee has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains('slug', $permission);
    }

    /**
     * Check if employee has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if employee has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if employee has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if employee has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Assign role to employee.
     */
    public function assignRole(Role|string $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove role from employee.
     */
    public function removeRole(Role|string $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->detach($role->id);
    }
}
