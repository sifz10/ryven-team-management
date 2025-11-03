<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'uat_project_id',
        'name',
        'email',
        'role',
        'last_accessed_at',
    ];

    protected $casts = [
        'last_accessed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(UatProject::class, 'uat_project_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(UatFeedback::class);
    }

    public function isInternal()
    {
        return $this->role === 'internal';
    }

    public function isExternal()
    {
        return $this->role === 'external';
    }
}

