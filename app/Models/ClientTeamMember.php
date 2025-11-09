<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'team_member_client_id',
        'name',
        'email',
        'role',
        'status',
        'invitation_token',
        'invitation_sent_at',
        'joined_at',
    ];

    protected $casts = [
        'invitation_sent_at' => 'datetime',
        'joined_at' => 'datetime',
    ];

    // The client who owns this team
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // The team member's own client account (for login)
    public function teamMemberClient()
    {
        return $this->belongsTo(Client::class, 'team_member_client_id');
    }

    public function projects()
    {
        return $this->hasManyThrough(
            Project::class,
            ProjectMember::class,
            'client_team_member_id', // Foreign key on project_members table
            'id', // Foreign key on projects table
            'id', // Local key on client_team_members table
            'project_id' // Local key on project_members table
        );
    }

    // Get project members for this team member
    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class, 'client_team_member_id');
    }
}
