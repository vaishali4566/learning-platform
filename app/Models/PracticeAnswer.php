<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option',
        'is_correct'
    ];

    public function attempt()
    {
        return $this->belongsTo(PracticeAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(PracticeQuestion::class, 'question_id');
    }
}
