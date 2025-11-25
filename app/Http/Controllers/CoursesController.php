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
}

