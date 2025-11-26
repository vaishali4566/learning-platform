<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PracticeTest;
use App\Models\PracticeQuestion;
use App\Models\PracticeAttempt;
use App\Models\PracticeAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserPracticeTestController extends Controller
{
    // Timer in minutes
    protected $timeLimitMinutes = 10;

    /**
     * Start page for the practice test (shows info + start button).
     * Route: GET /lesson/{lessonId}/practice-test
     */
    public function start($lessonId)
    {
        // Find the practice test attached to this lesson (assuming one test per lesson)
        $test = PracticeTest::where('lesson_id', $lessonId)->withCount('questions')->first();

        if (! $test) {
            abort(404, 'Practice test not found for this lesson.');
        }

        return view('user.practice.start', compact('test'));
    }

    /**
     * Create an attempt and redirect to first question.
     * This can be triggered from Start button (POST) or we create attempt in start() GET as well.
     * For clarity we create attempt on POST /lesson/{lessonId}/practice-test/start
     */
    public function createAttempt(Request $request, $lessonId)
    {
        $user = Auth::user();

        $test = PracticeTest::where('lesson_id', $lessonId)->with('questions')->first();
        if (! $test) {
            return redirect()->back()->with('error','Practice Test not found.');
        }

        // create attempt
        $attempt = PracticeAttempt::create([
            'user_id' => $user->id,
            'practice_test_id' => $test->id,
            'total_questions' => $test->questions->count(),
            'correct_answers' => 0,
            'score' => 0,
            'time_taken' => 0,
            'status' => 'in_progress',
            'started_at' => Carbon::now(),
        ]);

        // redirect to question view - pass attempt id and start at index 0
        return redirect()->route('user.practice.test', ['attemptId' => $attempt->id, 'q' => 0]);
    }

    /**
     * Show test question by question.
     * Route: GET /practice-attempt/{attemptId}/questions?q=0
     */
    public function showTest(Request $request, $attemptId)
    {
        $user = Auth::user();
        $attempt = PracticeAttempt::findOrFail($attemptId);

        // Security checks
        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('user.practice.result', $attempt->id);
        }

        $questions = PracticeQuestion::where('practice_test_id', $attempt->practice_test_id)
                        ->orderBy('id')
                        ->get();

        $total   = $questions->count();
        $qIndex  = max(0, (int) $request->query('q', 0));
        if ($qIndex >= $total) {
            $qIndex = $total - 1;
        }

        $question = $questions->get($qIndex);

        // Saved answer
        $saved = PracticeAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $question->id)
                    ->first();

        // ────────────────────── TIMER LOGIC (FIXED) ──────────────────────
        $startedAt = Carbon::parse($attempt->started_at);
        $endAt     = $startedAt->copy()->addMinutes($this->timeLimitMinutes); // your time limit

        // If time is already over → finalize immediately
        if (Carbon::now()->greaterThanOrEqualTo($endAt)) {
            $this->finalizeAttempt($attempt);
            return redirect()->route('user.practice.result', $attempt->id)
                ->with('info', 'Time is up. Test submitted automatically.');
        }

        // Send exact end time in milliseconds (for JS – no flash!)
        $endAtTimestamp = $endAt->timestamp * 1000;

        // ───────────────────────────────────────────────────────────────

        return view('user.practice.test', compact(
            'attempt',
            'question',
            'qIndex',
            'total',
            'saved',
            'endAtTimestamp'   // ← only this variable is needed now
        ));
    }

    /**
     * Submit single answer or finish test.
     * Route: POST /practice-attempt/{attemptId}/submit
     * Expected payload:
     * - question_id
     * - selected_option (a|b|c|d)
     * - action => 'next' | 'prev' | 'finish'
     */
    public function submitTest(Request $request, $attemptId)
    {
        $user = Auth::user();
        $attempt = PracticeAttempt::findOrFail($attemptId);

        if ($attempt->user_id !== $user->id) abort(403);
        if ($attempt->status === 'completed') {
            return redirect()->route('user.practice.result', $attempt->id);
        }

        $request->validate([
            'question_id' => 'required|integer|exists:practice_questions,id',
            'selected_option' => 'nullable|in:a,b,c,d',
            'action' => 'nullable|in:next,prev,finish'
        ]);

        $questionId = $request->question_id;
        $selected = $request->selected_option;
        $action = $request->action ?? 'next';

        // Save or update answer
        if ($selected !== null) {
            $question = PracticeQuestion::findOrFail($questionId);
            $isCorrect = (strtolower($selected) === strtolower($question->correct_option));

            $answer = PracticeAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionId],
                ['selected_option' => $selected, 'is_correct' => $isCorrect]
            );
        }

        // If action is finish -> finalize and go to result
        if ($action === 'finish') {
            $this->finalizeAttempt($attempt);
            return redirect()->route('user.practice.result', $attempt->id);
        }

        // Determine next/prev question index (we expect frontend to pass current index)
        $currentIndex = (int) $request->input('q', 0);
        if ($action === 'next') $nextIndex = $currentIndex + 1;
        else $nextIndex = max(0, $currentIndex - 1);

        // get total questions
        $total = PracticeQuestion::where('practice_test_id', $attempt->practice_test_id)->count();
        if ($nextIndex >= $total) {
            // if beyond last question, finish
            $this->finalizeAttempt($attempt);
            return redirect()->route('user.practice.result', $attempt->id);
        }

        return redirect()->route('user.practice.test', [
            'attemptId' => $attempt->id,
            'q' => $nextIndex
        ]);
    }

    /**
     * Show result page
     * Route: GET /practice-attempt/{attemptId}/result
     */
    public function result($attemptId)
    {
        $user = Auth::user();
        $attempt = PracticeAttempt::with('answers.question')->findOrFail($attemptId);

        if ($attempt->user_id !== $user->id) abort(403);

        if ($attempt->status !== 'completed') {
            // If still in progress, finalize before showing result
            $this->finalizeAttempt($attempt);
            $attempt = $attempt->fresh('answers.question');
        }

        return view('user.practice.result', compact('attempt'));
    }

    /**
     * Finalize attempt: calculate correct answers, score, time taken, set completed_at/status
     */
      protected function finalizeAttempt(PracticeAttempt $attempt)
    {
        if ($attempt->status === 'completed') return;

        $answers = PracticeAnswer::where('attempt_id', $attempt->id)->get();
        $correct = $answers->where('is_correct', true)->count();
        $total = $attempt->total_questions ?: PracticeQuestion::where('practice_test_id', $attempt->practice_test_id)->count();
        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        $startedAt = Carbon::parse($attempt->started_at);
        $completedAt = Carbon::now();

        // ✅ Correct direction
        $timeTakenSeconds = $startedAt->diffInSeconds($completedAt);

        $formattedTime = gmdate("H:i:s", $timeTakenSeconds);

        $attempt->update([
            'correct_answers' => $correct,
            'score' => $score,
            'time_taken' => $timeTakenSeconds,
            'time_taken_formatted' => $formattedTime,
            'status' => 'completed',
            'completed_at' => $completedAt,
        ]);

        return [
            'seconds' => $timeTakenSeconds,
            'formatted' => $formattedTime
        ];
    }


}
