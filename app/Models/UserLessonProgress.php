<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLessonProgress extends Model
{
    protected $table = 'user_lesson_progress';

    protected $fillable = [
        'user_id',
        'course_id',    
        'lesson_id',        
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // 🔗 Relation with Lesson
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // 🔗 Relation with User (optional but good)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
