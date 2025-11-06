<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's personal notes
     */
    public function personalNotes()
    {
        return $this->hasMany(PersonalNote::class);
    }

    /**
     * Get the user's saved emails
     */
    public function savedEmails()
    {
        return $this->hasMany(SavedEmail::class);
    }

    /**
     * Get the user's social media accounts
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the user's social media posts
     */
    public function socialPosts()
    {
        return $this->hasMany(SocialPost::class);
    }

    /**
     * Get the user's email accounts
     */
    public function emailAccounts()
    {
        return $this->hasMany(EmailAccount::class);
    }
}
