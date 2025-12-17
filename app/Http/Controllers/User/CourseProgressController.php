<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;

class CourseProgressController extends Controller
{
    public function show($courseId)
    {
        $userId = auth()->id();

        $total = CourseProgressService::totalLessons($courseId);
        $completed = CourseProgressService::completedLessons($userId, $courseId);
        $progress = CourseProgressService::progressPercentage($userId, $courseId);

        return response()->json([
            'course_id' => $courseId,
            'total_lessons' => $total,
            'completed_lessons' => $completed,
            'progress_percentage' => $progress,
            'is_completed' => $progress === 100
        ]);
    }
}
