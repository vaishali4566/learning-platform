<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeTest extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'total_questions'
    ];

    // Relations
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(PracticeQuestion::class);
    }
}
