<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class TrainerCourseController extends Controller
{
    public function index()     //show all courses
    {
        $courses = Course::all();
        return view('trainer.courses.index', compact('courses'));
    }

    public function create()    //show form to create a course
    {
        return view('trainer.courses.create');
    }

    public function store(Request $request) //store new course
    {
        $validator = Validator::make($request->all(), [
            'trainer_id'  => 'required|exists:trainers,id',
            'title'       => 'required|string|min:5|max:50|unique:courses,title',
            'description' => 'required|string|min:10|max:255',
            'price'       => 'required|numeric|min:0',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'is_online'   => 'nullable|boolean',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->only([
                'trainer_id',
                'title',
                'description',
                'price',
                'duration',
                'difficulty',
                'is_online',
                'status',
                'city',
                'country'
            ]);
            $course = Course::create($data);
            DB::commit();

            return response()->json([
                'message' => 'Course created successfully',
                'data' => $course,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading course details. Please try again later.'
            ], 500);
        }
    }

    public function myCourses() //show courses of authenticated trainer
    {
        $trainer = Auth::guard('trainer')->user();

        // Fetch all courses created by this trainer
        $courses = $trainer->courses;

        return view('trainer.courses.myCourses', compact('courses'));

    }

    public function explore($courseId)
    {
        return view('trainer.courses.explore', ['courseId' => $courseId]);
    }

    public function destroy(Course $course)
    {
        $trainer = Auth::guard('trainer')->user();

        // Ensure the authenticated trainer owns the course
        if ($course->trainer_id !== $trainer->id) {
            return redirect()->route('trainer.courses.my')->with('error', 'Unauthorized action.');
        }

        try {
            $course->delete();
            return redirect()->route('trainer.courses.my')->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Course deletion failed: ' . $e->getMessage());
            return redirect()->route('trainer.courses.my')->with('error', 'An error occurred while deleting the course. Please try again later.');
        }
    }
  public function destroy_lessson($courseId, $lessonId)
{
    try {
        $lesson = Lesson::where('course_id', $courseId)->where('id', $lessonId)->firstOrFail();
        $lesson->delete();

        return response()->json(['success' => true, 'message' => 'Lesson deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to delete lesson']);
    }
}


}
