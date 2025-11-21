<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class UserLessonController extends Controller
{

    public function viewLessons($id)
    {
        $courseName = Course::find($id)->title;
        return view('user.lessons.index', ['courseId' => $id, 'courseName' => $courseName]);
    }

    public function lessonsByCourse($id)
    {
        $lessons = Lesson::where('course_id', $id)->get();
        return response()->json($lessons);
    }

    public function getLessons($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)
            ->select('id', 'title', 'content_type')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($lessons);
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
        ], 200);
    }
}
