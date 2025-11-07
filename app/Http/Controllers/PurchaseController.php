<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // ✅ Show purchases for logged-in user or trainer
    public function index()
    {
        // 🔹 If trainer
        if (Auth::guard('trainer')->check()) {
            $trainerId = Auth::guard('trainer')->id();

            // Only purchases made by this trainer (exclude their own courses)
            $purchases = Purchase::with('course', 'payment')
                ->where('trainer_id', $trainerId) // direct column
                ->whereHas('course', function ($query) use ($trainerId) {
                    $query->where('trainer_id', '!=', $trainerId);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('trainer.courses.myPurchasedCourses', compact('purchases'));
        }

        // 🔹 Default: user
        $purchases = Purchase::with('course', 'payment')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.courses.myCourses', compact('purchases'));
    }
}
