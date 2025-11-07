<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_user_id',
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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientUser()
    {
        return $this->belongsTo(ClientUser::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'client_team_member_project');
    }
}
