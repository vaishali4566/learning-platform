<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
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

    public function store(Request $request)
    {
        $rules = [
            'course_id'     => 'required|exists:courses,id',
            'title'         => 'required|string|min:3|max:50',
            'content_type'  => 'required|in:video,text,quiz',
            'order_number'  => 'nullable|numeric',
        ];

        if ($request->content_type === 'video') {
            $rules['video'] = 'required|mimes:mp4,mov,ogg,webm|max:51200';
        } elseif ($request->content_type === 'text') {
            $rules['text_content'] = 'required|string|min:10';
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
            ];

            if ($request->content_type === 'video') {
                $file = $request->file('video');
                $path = $file->store('videos', 'public');
                $data['video_url'] = $path;
            } elseif ($request->content_type === 'text') {
                $data['text_content'] = $request->text_content;
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

    public function viewLesson($id)
    {
        return view('lessons.view', ['lessonId' => $id]);
    }

    public function viewLesson1($id)
    {
        // $course = Course::with('lessons')->findOrFail($id);
        // return view('lessons.alllessons1', ['course' => $course]);
        return view('trainer.lessons.index', ['courseId' => $id]);
    }

    

    public function lessonsByCourse($id)
    {
        $lessons = Lesson::where('course_id', $id)->get();
        return response()->json($lessons);
    }

    // use Illuminate\Support\Facades\Response;
    // use Illuminate\Support\Facades\Storage;

    // public function stream($filename)
    // {
    //     $path = storage_path("app/public/videos/{$filename}");

    //     if (!file_exists($path)) {
    //         abort(404);
    //     }

    //     $mime = mime_content_type($path);
    //     $headers = [
    //         'Content-Type' => $mime,
    //         'Accept-Ranges' => 'bytes',
    //     ];

    //     $size = filesize($path);
    //     $file = fopen($path, 'rb');

    //     $start = 0;
    //     $end = $size - 1;

    //     if (isset($_SERVER['HTTP_RANGE'])) {
    //         preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    //         $start = intval($matches[1]);
    //         if (isset($matches[2])) {
    //             $end = intval($matches[2]);
    //         }
    //         fseek($file, $start);
    //     }

    //     $length = $end - $start + 1;
    //     $headers['Content-Length'] = $length;
    //     $headers['Content-Range'] = "bytes $start-$end/$size";

    //     return response()->stream(function () use ($file, $length) {
    //         $buffer = 1024 * 8;
    //         $bytesSent = 0;
    //         while (!feof($file) && $bytesSent < $length) {
    //             $readLength = min($buffer, $length - $bytesSent);
    //             echo fread($file, $readLength);
    //             flush();
    //             $bytesSent += $readLength;
    //         }
    //         fclose($file);
    //     }, 206, $headers);
    // }

    public function stream($id, Request $request)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'message' => 'Lesson not found'
            ], 404);
        }

        switch ($lesson->content_type) {
            case 'video':
                return $this->streamVideo($lesson->video_url, $request);

            case 'text':
                return response()->json([
                    'content_type' => 'text',
                    'text_content' => $lesson->text_content
                ], 200);

            case 'quiz':
                return response()->json([
                    'content_type' => 'quiz',
                    'quiz_questions' => json_decode($lesson->quiz_questions)
                ], 200);

            default:
                return response()->json([
                    'message' => 'Unsupported content type.'
                ], 400);
        }
    }

    private function streamVideo($filePath, Request $request)
    {
        $storagePath = storage_path("app/public/{$filePath}");

        if (!file_exists($storagePath)) {
            return response()->json([
                'message' => 'Video file not found.'
            ], 404);
        }

        $mime = mime_content_type($storagePath);
        $size = filesize($storagePath);
        $start = 0;
        $length = $size;

        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
        ];

        if ($request->headers->has('Range')) {
            preg_match('/bytes=(\d+)-(\d*)/', $request->header('Range'), $matches);

            $start = intval($matches[1]);
            $end = $matches[2] !== '' ? intval($matches[2]) : $size - 1;
            $length = $end - $start + 1;

            $headers['Content-Range'] = "bytes $start-$end/$size";
            $headers['Content-Length'] = $length;

            $status = 206;
        } else {
            $headers['Content-Length'] = $length;
            $status = 200;
        }

        $stream = function () use ($storagePath, $start, $length) {
            $handle = fopen($storagePath, 'rb');
            fseek($handle, $start);
            echo fread($handle, $length);
            fclose($handle);
        };

        return response()->stream($stream, $status, $headers);
    }

    public function showLessonVideoPage()
    {
        return view('lessons.videoStream');
    }

    public function manage(Course $course)
    {
        $lessons = $course->lessons()->get();
        return view('trainer.lessons.manage', compact('course', 'lessons'));
    }
}
