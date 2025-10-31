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
    

    /**
     * Display all courses created by the logged-in trainer.
     * Route: trainer/courses
     */
    public function showTrainerCourses()
    {
        $trainerId = Auth::guard('trainer')->id() || 1;

        $courses = Course::with('trainer')
            ->where('trainer_id', $trainerId)
            ->get();

        return view('courses.trainercourses', compact('courses'));
    }

    /**
     * Show course statistics for the trainer — includes number of purchases and total revenue.
     * Route: trainer/courses/stats
     */
    public function coursesWithPurchaseCount()
    {
        $trainerId = Auth::guard('trainer')->id() || 1;

        $trainer = Trainer::with([
            'courses' => function ($query) {
                $query->withCount('payments')
                    ->withSum('payments', 'amount');
            }
        ])->findOrFail($trainerId);

        return view('courses.courseCount', compact('trainer'));
    }


    // =========================================================
    // 🧑‍🎓 USER SECTION
    // =========================================================

    /**
     * Show the "Explore Now" page for a specific course.
     * Route: /courses/explore/{courseId}
     */
    public function explore($courseId)
    {
        return view('courses.exploreNow', ['courseId' => $courseId]);
    }


    // =========================================================
    // 🌍 SHARED / COMMON METHODS (Used by both Users & Trainers)
    // =========================================================

    /**
     * Fetch all courses (used for API calls or AJAX).
     */
    public function getAll()
    {
        $courses = Course::all();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses
        ]);
    }

    /**
     * Get detailed info of a single course by ID.
     * Includes trainer details.
     */
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

    /**
     * Update course details (for trainer dashboard).
     * Handles optional image upload.
     */
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

    /**
     * Delete a course (Trainer access only).
     */
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
}
