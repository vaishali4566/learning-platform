<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TrainerCourseController extends Controller
{
    public function index()
    {
        $trainerId = Auth::guard('trainer')->id();

        // ✅ 1. My Courses (created by this trainer)
        $myCourses = Course::where('trainer_id', $trainerId)->get();

        // ✅ 2. Purchased Courses (courses trainer has purchased)
        $purchasedCourses = Purchase::with('course')
            ->where('trainer_id', $trainerId)
            ->get();

        // ✅ Get all purchased course IDs to exclude from available list
        $purchasedCourseIds = $purchasedCourses->pluck('course_id')->toArray();

        // ✅ 3. Available Courses (created by others & not purchased)
        $availableCourses = Course::where('trainer_id', '!=', $trainerId)
            ->whereNotIn('id', $purchasedCourseIds)
            ->get();

        // ✅ Return all three sets to the view
        return view('trainer.courses.index', compact(
            'myCourses',
            'availableCourses',
            'purchasedCourses'
        ));
    }

    public function explore($courseId)
    {
        $course = Course::with('trainer')->findOrFail($courseId);
        $trainerId = Auth::guard('trainer')->id(); // current trainer

        // Ownership check
        $isOwned = $course->trainer_id == $trainerId;

        // Purchase check
        $isPurchased = Purchase::where('trainer_id', $trainerId)
            ->where('course_id', $courseId)
            ->exists();

        // Pass data to Blade
        return view('trainer.courses.explore', [
            'course' => $course,
            'isOwned' => $isOwned,
            'isPurchased' => $isPurchased,
            'courseId' => $courseId
        ]);
    }

    public function create(){
        return view('trainer.courses.create');
    }

    public function update(Request $request, $id)
    {
        $trainer = Auth::guard('trainer')->user();
        $course = Course::findOrFail($id);

        // Ownership Check
        if ($course->trainer_id !== $trainer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|min:5|max:50|unique:courses,title,' . $id,
            'description' => 'required|string|min:10|max:255',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'nullable|string|max:50',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'is_online'   => 'nullable|boolean',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {

            $course->title = $request->title;
            $course->description = $request->description;
            $course->price = $request->price;
            $course->duration = $request->duration;
            $course->difficulty = $request->difficulty;
            $course->is_online = $request->is_online ?? false;
            $course->city = $request->city;
            $course->country = $request->country;

            // 🟢 If thumbnail updated, replace
            if ($request->hasFile('thumbnail')) {

                // Delete old thumbnail if exists
                if ($course->thumbnail && file_exists(public_path('storage/'.$course->thumbnail))) {
                    unlink(public_path('storage/'.$course->thumbnail));
                }

                $file = $request->file('thumbnail');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/thumbnails', $filename, 'public');

                $course->thumbnail = $path;
            }

            $course->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully!',
                'data' => $course,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating course.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|min:5|max:50|unique:courses,title',
            'description' => 'required|string|min:10|max:255',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'nullable|string|max:50',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'is_online'   => 'nullable|boolean',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // ✅ Automatically get logged-in trainer ID
            $trainer = auth('trainer')->user();
            if (!$trainer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Trainer not logged in.',
                ], 401);
            }

            $data = $request->only([
                'title',
                'description',
                'price',
                'duration',
                'difficulty',
                'is_online',
                'status',
                'city',
                'country',
            ]);

            $data['trainer_id'] = $trainer->id; // ✅ Set trainer_id automatically

            // ✅ Handle Thumbnail Upload
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/thumbnails', $filename, 'public');
                $data['thumbnail'] = $path;
            }

            $course = Course::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading course details. Please try again later.',
            ], 500);
        }
    }

    public function show($courseId)
    {
        $course = Course::with('trainer')->findOrFail($courseId);

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }


    public function myCourses()
    {
        $trainer = Auth::guard('trainer')->user();

        // Fetch all courses created by this trainer
        $courses = $trainer->courses;

        return view('trainer.courses.myCourses', compact('courses'));
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

}
