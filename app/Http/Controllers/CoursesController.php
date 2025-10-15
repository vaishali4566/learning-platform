<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer_id'  => 'required|exists:trainers,id',
            'title'       => 'required|string|min:3|max:50',
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

    public function showCreateForm()
    {
        return view('courses.create');
    }
}
