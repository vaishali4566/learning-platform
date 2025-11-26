<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content_type',
        'practice_test_id',
        'video_url',
        'text_content',
        'quiz_questions',
        'order_number',
        'duration',
    ];

    protected $casts = [
        'content_type' => 'string',
        'quiz_questions' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'lesson_id');
    }



    public function practice()
    {
        return $this->hasOne(PracticeTest::class, 'lesson_id');
    }
}