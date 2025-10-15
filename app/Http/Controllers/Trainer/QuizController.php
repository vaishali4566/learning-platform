<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;

class QuizController extends Controller
{
    // 1️⃣ Show list of quizzes (dummy)
    public function index()
    {
        $quizzes = Quiz::all(); // just fetch all for now
        return view('trainer.quizzes.index', compact('quizzes'));
    }

    // 2️⃣ Show form to create a new quiz
    public function create()
    {
        return view('trainer.quizzes.create');
    }

    // 3️⃣ Store new quiz
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'lesson_id.required' => 'Lesson ID is required.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'title.required' => 'Quiz title is required.',
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



    // 4️⃣ Show form to edit quiz (add questions)
    public function edit($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('trainer.quizzes.edit', compact('quiz'));
    }

    public function storeQuestion(Request $request, $quizId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|between:0,3',
        ], [
            'question_text.required' => 'Question text is required.',
            'marks.required' => 'Marks for this question are required.',
            'marks.integer' => 'Marks must be a number.',
            'marks.min' => 'Marks must be at least 1.',
            'options.required' => 'All 4 options are required.',
            'options.size' => 'You must provide exactly 4 options.',
            'options.*.required' => 'Each option cannot be empty.',
            'correct_option.required' => 'Please select the correct option index.',
            'correct_option.between' => 'Correct option must be between 0 and 3.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quizId,
            'question_text' => $request->question_text,
            'marks' => $request->marks,
            'options' => $request->options,
            'correct_option' => $request->correct_option,
        ]);

        return redirect()->back()->with('success', 'Question added successfully.');
    }


    public function deleteQuestion($questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $quizId = $question->quiz_id;
        $question->delete();

        $quiz = Quiz::find($quizId);
        $totalMarks = $quiz->questions()->sum('marks');
        $quiz->total_marks = $totalMarks;
        $quiz->passing_marks = ceil($totalMarks * 0.33);
        $quiz->save();

        return redirect()->back()->with('success', 'Question deleted and total/passing marks updated.');
    }
    public function finalizeQuiz($quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);

        if ($quiz->questions->count() == 0) {
            return redirect()->back()->withErrors(['quiz' => 'You must add at least one question before finalizing the quiz.']);
        }

        $totalMarks = $quiz->questions->sum('marks');
        $quiz->total_marks = $totalMarks;
        $quiz->passing_marks = ceil($totalMarks * 0.33);
        $quiz->save();

        return redirect()->route('trainer.quizzes.edit', $quizId)
            ->with('success', 'Quiz finalized! Total and passing marks saved.');
    }
}
