<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CoursesController extends Controller
{

    public function create()
    {
        return view('courses.create');
    }

    public function index()
    {
        $courses = Course::all();

        return view('courses.index', compact('courses'));
    }
    
    public function store(Request $request)
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

    public function getAll()
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
        $userId = Auth::id();
        $courses = User::findOrFail($userId)->courses;
        return view('courses.mycourses', compact('courses'));
    }

    public function showTrainerCourses()
    {
        $trainerId = Auth::guard('trainer')->id();
        $courses = Course::with('trainer')->where('trainer_id', $trainerId)->get();
        return view('courses.trainercourses', compact('courses'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $course = Course::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image && Storage::exists($course->image)) {
                Storage::delete($course->image);
            }

            $path = $request->file('image')->store('courses', 'public');
            $course->image = $path;
        }

        $course->title = $request->title;
        $course->description = $request->description;
        $course->save();

        return response()->json([
            'success' => true,
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'image_url' => asset('storage/' . $course->image),
            ]
        ]);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
        
        try {
            if ($course->image && Storage::exists($course->image)) {
                Storage::delete($course->image);
            }
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

        return response()->json(['success' => true]);
    }

    public function explore($courseId)
    {
        return view('courses.exploreNow', ['courseId' => $courseId]);
    }

    public function coursesWithPurchaseCount()
    {
        $trainerId = Auth::guard('trainer')->id() || 1;
        $trainer = Trainer::with(['courses' => function ($query) {
            $query->withCount('payments')
                ->withSum('payments', 'amount');
        }])->findOrFail($trainerId);

        return view('courses.courseCount', compact('trainer'));
    }    
}
