<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trainer;

class AdminController extends Controller
{
        
    public function fetchAllUsers(Request $request)
    {
      
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all users successfully',
            'users' => $users
        ], 200);
    }
    public function fetchAllTrainers(Request $request)
    {
        $trainers = Trainer::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all trainers successfully', 
            'trainers' => $trainers
        ], 200);
    }

}
