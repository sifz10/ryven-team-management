<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UatFeedback extends Model
{
    use HasFactory;

    protected $table = 'uat_feedbacks';

    protected $fillable = [
        'uat_test_case_id',
        'uat_user_id',
        'comment',
        'status',
        'attachment_path',
    ];

    public function testCase()
    {
        return $this->belongsTo(UatTestCase::class, 'uat_test_case_id');
    }

    public function user()
    {
        return $this->belongsTo(UatUser::class, 'uat_user_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'passed' => 'green',
            'failed' => 'red',
            'blocked' => 'orange',
            'pending' => 'gray',
            default => 'gray',
        };
    }
}

