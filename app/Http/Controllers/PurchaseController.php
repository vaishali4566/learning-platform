<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // ✅ Show all purchases for logged-in user
    public function index()
    {
        $purchases = Purchase::with('course', 'payment')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Matches: resources/views/user/courses/myCourses.blade.php
        return view('user.courses.myCourses', compact('purchases'));
    }
}
