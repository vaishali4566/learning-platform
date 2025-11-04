<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class UserLessonController extends Controller
{

    public function viewLessons($id)
    {
        $courseName = Course::find($id)->title;
        return view('user.lessons.index', ['courseId' => $id, 'courseName' => $courseName]);
    }

    public function lessonsByCourse($id)
    {
        $lessons = Lesson::where('course_id', $id)->get();
        return response()->json($lessons);
    }
}
