<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseFeedback;
use Illuminate\Support\Facades\Http;

class CourseFeedbackController extends Controller
{
    /**
     * GET: Fetch all feedback for a course
     */
    public function index($courseId)
    {
        $feedback = CourseFeedback::with('user:id,name')
            ->where('course_id', $courseId)
            ->latest()
            ->get();

        return response()->json($feedback);
    }

    /**
     * POST: Submit feedback + broadcast socket event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();

        // ❗ Prevent duplicate feedback (Laravel-level check)
        if (CourseFeedback::where('course_id', $validated['course_id'])
            ->where('user_id', $validated['user_id'])
            ->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'You have already submitted feedback for this course.'
            ], 409);
        }

        // Create feedback
        $feedback = CourseFeedback::create($validated)->load('user:id,name');

        // SOCKET PAYLOAD
        $payload = [
            "courseId"   => $feedback->course_id,
            "feedbackId" => $feedback->id,
            "user"       => $feedback->user->name,
            "rating"     => $feedback->rating,
            "comment"    => $feedback->comment,
            "created_at" => $feedback->created_at->toDateTimeString(),
        ];

        // Send event to Node.js
        try {
            Http::post("http://127.0.0.1:4000/broadcast-feedback", $payload);
        } catch (\Exception $e) {
            \Log::error("Feedback Socket Error: " . $e->getMessage());
        }

        return response()->json([
            "status"  => true,
            "message" => "Feedback submitted successfully",
            "data"    => $feedback
        ]);
    }

    /**
     * GET: Rating Summary for dynamic UI
     */
    public function summary($courseId)
    {
        $feedback = CourseFeedback::where('course_id', $courseId);

        $total   = $feedback->count();
        $average = round($feedback->avg('rating') ?? 0, 1);

        // Optimized counts query (fast)
        $counts = $feedback->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        return response()->json([
            "average" => $average,
            "total"   => $total,
            "counts"  => [
                '5' => $counts[5] ?? 0,
                '4' => $counts[4] ?? 0,
                '3' => $counts[3] ?? 0,
                '2' => $counts[2] ?? 0,
                '1' => $counts[1] ?? 0,
            ]
        ]);
    }

    /**
     * GET: Check if logged-in user already gave feedback
     */
    public function checkUserFeedback($courseId)
    {
        $hasFeedback = CourseFeedback::where('course_id', $courseId)
            ->where('user_id', auth()->id())
            ->exists();

        return response()->json([
            'hasFeedback' => $hasFeedback
        ]);
    }
}
