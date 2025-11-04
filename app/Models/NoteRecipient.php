<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteRecipient extends Model
{
    protected $fillable = [
        'personal_note_id',
        'email',
    ];

    public function personalNote(): BelongsTo
    {
        return $this->belongsTo(PersonalNote::class);
    }
}
