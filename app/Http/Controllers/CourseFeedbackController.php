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

        // Attach logged-in user
        $validated['user_id'] = auth()->id();

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
            // Fail silently so user experience is not broken
            \Log::error("Feedback Socket Error: " . $e->getMessage());
        }

        return response()->json([
            "status"  => true,
            "message" => "Feedback submitted successfully",
            "data"    => $feedback
        ]);
    }
}
