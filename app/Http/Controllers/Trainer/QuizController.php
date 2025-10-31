<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizController extends Controller
{
    // 🟢 Show all quizzes
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->get();
        return view('trainer.quizzes.index', compact('quizzes'));
    }

    // 🟢 Show quiz creation form
    public function create()
    {
        return view('trainer.quizzes.create');
    }

    // 🟢 Store new quiz
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'description' => $request->description,
            'total_marks' => 0,
            'passing_marks' => 0,
        ]);

        return redirect()->route('trainer.quizzes.edit', $quiz->id)
                         ->with('success', 'Quiz created. Now add questions.');
    }

    // 🟢 Show quiz edit page with all questions
    public function edit($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('trainer.quizzes.edit', compact('quiz'));
    }
    public function showQuestions($quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        return view('trainer.quizzes.questions', compact('quiz'));
    }

    // 🟢 Store new question (AJAX)
    public function storeQuestion(Request $request, $quizId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|between:0,3',
        ]);

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'question_text' => $request->question_text,
            'marks' => $request->marks,
            'options' => $request->options,
            'correct_option' => $request->correct_option,
        ]);

        // Update quiz marks
        $quiz = Quiz::find($quizId);
        $totalMarks = $quiz->questions()->sum('marks');
        $quiz->update([
            'total_marks' => $totalMarks,
            'passing_marks' => ceil($totalMarks * 0.33),
        ]);

        // Return response for AJAX
        return response()->json([
            'success' => true,
            'question' => [
                'id' => $question->id,
                'text' => $question->question_text,
                'marks' => $question->marks,
            ],
        ]);
    }

    // 🟢 Delete question (AJAX)
    public function deleteQuestion($questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $quizId = $question->quiz_id;
        $question->delete();

        // Update quiz marks
        $quiz = Quiz::find($quizId);
        $totalMarks = $quiz->questions()->sum('marks');
        $quiz->update([
            'total_marks' => $totalMarks,
            'passing_marks' => ceil($totalMarks * 0.33),
        ]);

        return response()->json(['success' => true]);
    }

    // 🟢 Finalize quiz (AJAX)
    public function finalizeQuiz($quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);

        if ($quiz->questions->isEmpty()) {
            return response()->json([
                'errors' => ['quiz' => ['You must add at least one question before finalizing.']]
            ], 422);
        }

        $totalMarks = $quiz->questions->sum('marks');
        $quiz->update([
            'total_marks' => $totalMarks,
            'passing_marks' => ceil($totalMarks * 0.33),
        ]);

        return response()->json(['success' => 'Quiz finalized successfully!']);
    }
}
