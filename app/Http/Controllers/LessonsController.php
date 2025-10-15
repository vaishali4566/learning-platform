<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'course_id'     => 'required|exists:courses,id',
            'title'         => 'required|string|min:3|max:50',
            'content_type'  => 'required|in:video,text,quiz',
            'order_number'  => 'nullable|numeric',
            'duration'      => 'nullable|string|max:50',
        ];

        if ($request->content_type === 'video') {
            $rules['video'] = 'required|mimes:mp4,mov,ogg,webm|max:51200';
        } elseif ($request->content_type === 'text') {
            $rules['text_content'] = 'required|string|min:10';
        } elseif ($request->content_type === 'quiz') {
            $rules['quiz_questions'] = 'required|json';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'course_id'    => $request->course_id,
                'title'        => $request->title,
                'content_type' => $request->content_type,
                'order_number' => $request->order_number,
                'duration'     => $request->duration,
            ];

            if ($request->content_type === 'video') {
                $file = $request->file('video');
                $path = $file->store('videos', 'public');
                $data['video_url'] = $path;
            } elseif ($request->content_type === 'text') {
                $data['text_content'] = $request->text_content;
            } elseif ($request->content_type === 'quiz') {
                $data['quiz_questions'] = $request->quiz_questions;
            }

            $lesson = Lesson::create($data);

            DB::commit();

            return response()->json([
                'message' => 'Lesson created successfully',
                'data' => $lesson,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lesson creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading lesson details. Please try again later.'
            ], 500);
        }
    }

    public function showLessonForm()
    {
        return view('lessons.create');
    }
}
