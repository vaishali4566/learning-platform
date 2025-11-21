<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;

class UserQuizController extends Controller
{
    // 1️⃣ List of quizzes
    public function index()
    {
        $quizzes = Quiz::all();
        return view('user.quizzes.index', compact('quizzes'));
    }

    // 2️⃣ Show quiz to user
    public function show(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('user.quizzes.show', compact('quiz'));
    }

    // 3️⃣ Submit quiz
    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::guard('web')->user();
        $answers = $request->input('answers');

        $totalScore = 0;

        foreach ($answers as $questionId => $selectedOption) {
            $question = QuizQuestion::find($questionId);
            $isCorrect = ($question->correct_option == $selectedOption);

            QuizAnswer::create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'user_id' => $user->id,
                'selected_option' => $selectedOption,
                'answer_text' => $question->options[$selectedOption] ?? null,
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) $totalScore += $question->marks;
        }

        // Redirect to result page
        return redirect()->route('user.quizzes.result', $quiz->id);
    }

    // 4️⃣ Show quiz result
    public function result(Quiz $quiz)
    {
        $user = Auth::guard('web')->user();

        $quiz->load('questions');
        $answers = QuizAnswer::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('question_id');

        $totalMarks = $quiz->total_marks ?? $quiz->questions->sum('marks');
        $score = $answers->where('is_correct', true)->sum(function ($ans) {
            return $ans->question->marks ?? 0;
        });

        return view('user.quizzes.result', compact('quiz', 'answers', 'score', 'totalMarks'));
    }
}
