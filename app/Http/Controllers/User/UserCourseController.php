<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\CourseProgressService;

class UserCourseController extends Controller
{
   public function index()
    {
        $userId = Auth::id();

        $purchasedCourseIds = Purchase::where('user_id', $userId)
            ->pluck('course_id');

        $purchasedCourses = Course::whereIn('id', $purchasedCourseIds)->get();

        $availableCourses = Course::whereNotIn('id', $purchasedCourseIds)->get();

        return view('user.courses.index', compact('purchasedCourses', 'availableCourses'));
    }

    public function myCourses()
    {
        $userId = Auth::id();

        $courses = Course::whereIn(
            'id',
            Purchase::where('user_id', $userId)->pluck('course_id')
        )->get();

        foreach ($courses as $course) {
            // ✅ Progress 0-100
            $course->progress = CourseProgressService::getProgress($userId, $course->id);

            // ✅ Check if certificate exists
            $course->certificateExists = Certificate::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('user.courses.myCourses', compact('courses'));
    }


    
    public function explore($courseId)
    {
        $user = Auth::guard('web')->user(); // current logged-in user
        $isPurchased = false;

        if ($user) {
            $isPurchased = Purchase::where('user_id', $user->id)
                            ->where('course_id', $courseId)
                            ->exists();
        }

        // Fetch course with trainer
        $course = Course::with('trainer')->findOrFail($courseId);

        return view('user.courses.explore', [
            'courseId' => $courseId,
            'isPurchased' => $isPurchased,
            'course' => $course
        ]);
    }

    public function show($courseId)
    {
        $course = Course::with('trainer')->findOrFail($courseId);

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }
}
