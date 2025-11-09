<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'logo',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'website',
        'status',
        'notes',
        'password',
        'otp_code',
        'otp_expires_at',
        'must_change_password',
        'last_login_at',
    ];

    protected $casts = [
        'status' => 'string',
        'otp_expires_at' => 'datetime',
        'must_change_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(ClientTeamMember::class);
    }

    // If this client is a team member, get their team member record
    public function teamMember()
    {
        return $this->hasOne(ClientTeamMember::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            default => 'gray'
        };
    }
}
