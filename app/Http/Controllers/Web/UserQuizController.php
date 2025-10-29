<?php
namespace App\Http\Controllers\Web;

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
        $quizzes = Quiz::all(); // ya filter by lesson if needed
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
        $answers = $request->input('answers'); // array => question_id => selected_option

        $totalScore = 0;

        foreach($answers as $questionId => $selectedOption) {
            $question = QuizQuestion::find($questionId);
            $isCorrect = ($question->correct_option == $selectedOption);

            QuizAnswer::create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'user_id' => $user->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
            ]);

            if($isCorrect) $totalScore += $question->marks;
        }

        return redirect()->route('user.quizzes.index')->with('success', "Quiz submitted! Your score: $totalScore / {$quiz->total_marks}");
    }
}
