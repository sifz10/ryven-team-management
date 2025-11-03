<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UatProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unique_token',
        'status',
        'created_by',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->unique_token)) {
                $project->unique_token = Str::random(32);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->hasMany(UatUser::class);
    }

    public function testCases()
    {
        return $this->hasMany(UatTestCase::class)->orderBy('order');
    }

    public function getPublicUrlAttribute()
    {
        return url('/uat/public/' . $this->unique_token);
    }
}

