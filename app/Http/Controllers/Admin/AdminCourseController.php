<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class AdminCourseController extends Controller
{
    /**
     * Course Page
     */
    public function showCoursePage()
    {
        return view('admin.courses');
    }

    /**
     * Get All Courses
     */
    public function fetchAllCourses()
    {
        $courses = Course::with('trainer:id,name,email')->latest()->get();

        return response()->json([
            'status' => 'success',
            'courses' => $courses
        ]);
    }

    /**
     * Approve / Reject Course
     */
    public function updateStatus(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $course->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete Course
     */
    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
