<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeQuestion;

class PracticeTestController extends Controller
{
    public function index()
    {
        return view('practiceTest.practiceTest');
    }

    public function getQuestions()
    {
        // Fetch 10 random questions
        $questions = PracticeQuestion::inRandomOrder()
            ->limit(10)
            ->get(['id', 'question', 'options', 'correct_answer']);

        return response()->json($questions);
    }
}
