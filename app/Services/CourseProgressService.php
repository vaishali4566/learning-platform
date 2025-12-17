<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\UserLessonProgress;

class CourseProgressService
{
    public static function totalLessons(int $courseId): int
    {
        return Lesson::where('course_id', $courseId)->count();
    }

    public static function completedLessons(int $userId, int $courseId): int
    {
        return UserLessonProgress::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->whereHas('lesson', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->count();
    }

    public static function progressPercentage(int $userId, int $courseId): int
    {
        $total = self::totalLessons($courseId);

        if ($total === 0) {
            return 0;
        }

        $completed = self::completedLessons($userId, $courseId);

        return (int) round(($completed / $total) * 100);
    }
}
