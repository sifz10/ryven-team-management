<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMember extends Model
{
    protected $fillable = [
        'project_id',
        'employee_id',
        'client_team_member_id',
        'client_member_name',
        'client_member_email',
        'member_type',
        'role',
        'responsibilities',
    ];

    protected $casts = [
        'member_type' => 'string',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function clientTeamMember(): BelongsTo
    {
        return $this->belongsTo(ClientTeamMember::class, 'client_team_member_id');
    }
}
