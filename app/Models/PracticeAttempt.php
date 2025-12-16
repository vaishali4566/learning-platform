<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'practice_test_id',
        'total_questions',
        'correct_answers',
        'score',
        'time_taken',
        'status',
        'started_at',
        'completed_at'
    ];

    // ⭐ IMPORTANT FIX
    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function answers()
    {
        return $this->hasMany(PracticeAnswer::class, 'attempt_id');
    }

    public function test()
    {
        return $this->belongsTo(PracticeTest::class, 'practice_test_id');
    }
}
