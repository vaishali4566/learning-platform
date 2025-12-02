<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class AdminTrainerController extends Controller
{
    /**
     * Trainer View Page
     */
    public function showTrainerPage()
    {
        return view('admin.trainers');
    }

    /**
     * Fetch all trainers
     */
    public function fetchAllTrainers()
    {
        return response()->json([
            'status' => 'success',
            'trainers' => Trainer::all()
        ]);
    }

    /**
     * Add Trainer
     */
    public function addTrainer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|min:6',
            'qualification' => 'nullable',
            'specialization' => 'nullable',
            'country' => 'nullable|string|max:100',
        ]);

        Trainer::create([
            ...$validated,
            'password' => bcrypt($validated['password']),
            'approved' => false
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Update Trainer
     */
    public function updateTrainer(Request $request, $id)
    {
        $trainer = Trainer::find($id);
        if (!$trainer) return response()->json(['status' => 'error']);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'qualification' => 'nullable',
            'specialization' => 'nullable',
            'country' => 'nullable',
        ]);

        $trainer->update($validated);
        return response()->json(['status' => 'success']);
    }

    /**
     * Delete Trainer
     */
    public function deleteTrainer($id)
    {
        $trainer = Trainer::find($id);
        if (!$trainer) return response()->json(['status' => 'error']);

        $trainer->delete();
        return response()->json(['status' => 'success']);
    }
}
