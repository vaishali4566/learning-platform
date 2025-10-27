<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $name = Auth::guard('trainer')->user()->name;
        return view('trainer.anudashboard', compact('name'));
    }
}
