<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{

    public function showCreateForm()
    {
        return view('courses.create');
    }

    public function index()
    {
        $courses = Course::all();

        return view('courses.index', compact('courses'));
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer_id'  => 'required|exists:trainers,id',
            'title'       => 'required|string|min:3|max:50|unique:courses,title',
            'description' => 'required|string|min:5|max:255',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|string|max:50',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'is_online'   => 'nullable|boolean',
            'status'      => 'nullable|string|in:pending,approved,rejected',
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

    public function showPage($id)
    {
        return view('courses.show', ['courseId' => $id]);
    }

    public function getAllCourse()
    {
        $courses = Course::all();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses
        ]);
    }

    public function getCourse($id)
    {
        $course = Course::with('trainer')->findOrFail($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => $course
        ]);
    }

    public function myCourses()
    {
        $courses = Course::all();

        return view('courses.mycourses', compact('courses'));
    }


    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'trainer_id'  => 'sometimes|required|exists:trainers,id',
            'title'       => 'sometimes|required|string|min:3|max:50|unique:courses,title,' . $course->id,
            'description' => 'sometimes|required|string|min:5|max:255',
            'price'       => 'sometimes|required|numeric|min:0',
            'duration'    => 'sometimes|required|string|max:50',
            'difficulty'  => 'sometimes|required|in:beginner,intermediate,advanced',
            'is_online'   => 'nullable|boolean',
            'status'      => 'nullable|string|in:pending,approved,rejected',
            'city'        => 'sometimes|required|string|max:100',
            'country'     => 'sometimes|required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $course->update($request->all());

            return response()->json([
                'message' => 'Course updated successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            Log::error('Course update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update course'
            ], 500);
        }
    }

    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        try {
            $course->delete();
            return response()->json([
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Course deletion failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete course'
            ], 500);
        }
    }

    public function showExplorePage($courseId)
    {
        return view('courses.exploreNow', ['courseId' => $courseId]);
    }
    
}
