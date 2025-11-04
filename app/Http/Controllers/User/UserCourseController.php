<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('user.courses.index', compact('courses'));
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
        return view('user.courses.explore', ['courseId' => $courseId]);
    }
}
