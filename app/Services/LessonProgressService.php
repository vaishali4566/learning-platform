<?php

namespace App\Services;

use App\Models\UserLessonProgress;
use App\Models\Lesson;
use Carbon\Carbon;

class LessonProgressService
{
    public static function markCompleted(int $userId, int $lessonId): void
    {
        // Check if already completed
        $exists = UserLessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->exists();

        if ($exists) {
            return;
        }

        $lesson = Lesson::findOrFail($lessonId);

        UserLessonProgress::create([
            'user_id'      => $userId,
            'course_id'    => $lesson->course_id,
            'lesson_id'    => $lessonId,
            'completed_at' => Carbon::now(),
        ]);
    }
}
