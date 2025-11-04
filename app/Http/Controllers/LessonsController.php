<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    public function viewLesson($id)
    {
        return view('lessons.view', ['lessonId' => $id]);
    }

    public function viewLesson1($id)
    {
        // $course = Course::with('lessons')->findOrFail($id);
        // return view('lessons.alllessons1', ['course' => $course]);
        return view('lessons.alllessons1', ['courseId' => $id]);
    }

    public function lessonsByCourse($id)
    {
        $lessons = Lesson::where('course_id', $id)->get();
        return response()->json($lessons);
    }

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
