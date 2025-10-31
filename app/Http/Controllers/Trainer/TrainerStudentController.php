<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainerStudentController extends Controller
{
    // Show all students grouped by course
    public function index()
    {
        $trainer = Auth::guard('trainer')->user();

        // Load trainer’s courses with student purchases and user info
        $courses = Course::with(['purchases.user'])
            ->where('trainer_id', $trainer->id)
            ->get();

        return view('trainer.students.index', compact('courses'));
    }
}
