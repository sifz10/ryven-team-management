<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
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
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function user()
    {
        return $this->hasOne(ClientUser::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(ClientTeamMember::class);
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
