<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;

class AdminQuizController extends Controller
{
    // Display all quizzes
    public function index()
    {
        $quizzes = Quiz::all();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    // Show create form
    public function create()
    {
        return view('admin.quizzes.create');
    }

    // Store quiz data
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer',
            'passing_marks' => 'required|integer',
        ]);

        Quiz::create($request->all());

        return redirect()->route('quizzes.index')->with('success', 'Quiz Created Successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        return view('admin.quizzes.edit', compact('quiz'));
    }

    // Update quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $request->validate([
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer',
            'passing_marks' => 'required|integer',
        ]);

        $quiz->update($request->all());

        return redirect()->route('quizzes.index')->with('success', 'Quiz Updated Successfully!');
    }

    // Delete a quiz
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('quizzes.index')->with('success', 'Quiz Deleted Successfully!');
    }
}
