<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCourseController extends Controller
{
   public function index()
    {
        $userId = Auth::id();

        // ✅ Get purchased course IDs
        $purchasedCourseIds = DB::table('payments')
            ->where('user_id', $userId)
            ->pluck('course_id');

        // ✅ Get purchased courses as models
        $purchasedCourses = Course::whereIn('id', $purchasedCourseIds)->get();

        // ✅ Get available courses (not purchased)
        $availableCourses = Course::whereNotIn('id', $purchasedCourseIds)->get();

        return view('user.courses.index', compact('purchasedCourses', 'availableCourses'));
    }



    public function myCourses()
    {
        $userId = Auth::id();
        $courses = DB::table('payments')
            ->join('courses', 'payments.course_id', '=', 'courses.id')
            ->where('payments.user_id', $userId)
            ->select('courses.*')
            ->get();

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
}
