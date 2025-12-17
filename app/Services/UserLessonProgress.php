<?php

namespace App\Services;

use App\Models\UserLessonProgress;

class CourseProgressService
{
    public static function completedLessonsCount(int $userId, int $courseId): int
    {
        return UserLessonProgress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->count();
    }
}
