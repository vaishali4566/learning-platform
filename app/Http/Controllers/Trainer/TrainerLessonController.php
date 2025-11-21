<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TrainerLessonController extends Controller
{
    public function create()
    {
        return view('trainer.lessons.create');
    }

    // Controller
    public function stream($id, Request $request)
    {
        $lesson = Lesson::findOrFail($id);

        // === VIDEO STREAMING (WITH RANGE SUPPORT - SUPER FAST) ===
        if ($lesson->content_type === 'video' && $lesson->video_url) {
            $filePath = storage_path('app/public/' . $lesson->video_url);

            if (!file_exists($filePath)) {
                abort(404, 'Video file not found.');
            }

            $size = filesize($filePath);
            $mime = mime_content_type($filePath) ?: 'video/mp4';
            $start = 0;
            $end = $size - 1;
            $length = $size;

            $headers = [
                'Content-Type'  => $mime,
                'Accept-Ranges' => 'bytes',
                'Content-Length' => $length,
            ];

            if ($request->headers->has('Range')) {
                preg_match('/bytes=(\d+)-(\d*)/', $request->header('Range'), $matches);
                $start = intval($matches[1]);
                $end = $matches[2] ? intval($matches[2]) : $size - 1;
                $length = $end - $start + 1;

                $headers['Content-Length'] = $length;
                $headers['Content-Range'] = "bytes $start-$end/$size";

                $statusCode = 206; // Partial Content
            } else {
                $statusCode = 200;
            }

            $stream = function () use ($filePath, $start, $length) {
                $handle = fopen($filePath, 'rb');
                fseek($handle, $start);
                $buffer = 1024 * 1024; // 1MB chunks
                while ($length > 0 && !feof($handle)) {
                    $read = min($buffer, $length);
                    echo fread($handle, $read);
                    $length -= $read;
                    flush();
                }
                fclose($handle);
            };

            return response()->stream($stream, $statusCode, $headers);
        }

        // === NON-VIDEO CONTENT (Text, Practice Test, Quiz) ===
        return response()->json([
            'content_type'     => $lesson->content_type,
            'text_content'     => $lesson->text_content,
            'quiz_id'          => $lesson->quiz ? $lesson->quiz->id : null,
            'practice_id'      => $lesson->practice ? $lesson->practice->id : null,
            
        ], 200);

    }

    public function store(Request $request)
    {
        // -----------------------------
        // VALIDATION RULES
        // -----------------------------
        $rules = [
            'course_id'     => 'required|exists:courses,id',
            'title'         => 'required|string|min:3|max:50',
            'content_type'  => 'required|in:video,text,quiz,practice',
        ];

        if ($request->content_type === 'video') {
            $rules['video'] = 'required|mimes:mp4,mov,ogg,webm|max:51200';
        } 
        elseif ($request->content_type === 'text') {
            $rules['text_content'] = 'required|string|min:10';
        } 
        elseif ($request->content_type === 'practice') {
            $rules['text_content'] = 'nullable|string'; 
        }
        elseif ($request->content_type === 'quiz') {
            // For now, only validation placeholder (if needed)
            $rules['text_content'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // -----------------------------
        // STORE LESSON
        // -----------------------------
        DB::beginTransaction();
        try {
            $data = [
                'course_id'    => $request->course_id,
                'title'        => $request->title,
                'content_type' => $request->content_type,
            ];

            // Handle Video
            if ($request->content_type === 'video') {
                $file = $request->file('video');
                $path = $file->store('videos', 'public');
                $data['video_url'] = $path;
            }

            // Handle Text
            if ($request->content_type === 'text') {
                $data['text_content'] = $request->text_content;
            }

            // Handle Practice Test
            if ($request->content_type === 'practice') {
                $data['text_content'] = $request->text_content ?? null;
            }

            // Handle Quiz (placeholder)
            if ($request->content_type === 'quiz') {
                // Later: Add quiz schema
                $data['text_content'] = $request->text_content ?? null;
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
    public function viewLessons($id)
    {
        $course = Course::with(['lessons.quiz'])->findOrFail($id);

        return view('trainer.lessons.index', [
            'course' => $course,
            'courseId' => $course->id,
            'courseName' => $course->title,
        ]);
    }


    public function getLessons($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)
            ->select('id', 'title', 'content_type')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($lessons);
    }

    public function manage(Course $course)
    {
        $lessons = $course->lessons()->get();
        return view('trainer.lessons.manage', compact('course', 'lessons'));
    }
}
