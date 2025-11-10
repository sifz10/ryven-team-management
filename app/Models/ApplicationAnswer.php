<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'job_question_id',
        'answer_text',
        'answer_file_path',
        'answer_file_type',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function question()
    {
        return $this->belongsTo(JobQuestion::class, 'job_question_id');
    }

    public function getAnswerFileUrlAttribute()
    {
        if ($this->answer_file_path) {
            return asset('storage/' . $this->answer_file_path);
        }
        return null;
    }
}
