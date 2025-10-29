<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Trainer;
use App\Models\Course;

class AdminProfileController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.dashboard', compact('admin'));
    }

    /**
     * Show admin profile
     */
    public function profile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    
    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('admin_profiles', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete admin account
     */
    public function deleteAccount(Request $request)
    {
        $admin = Auth::user();

        Auth::logout();

        if ($admin->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('success', 'Admin account deleted successfully.');
        }

        return back()->with('error', 'There was an error deleting your account.');
    }

    public function showUserPage()
    {
        // just return the blade view — no data directly passed
        return view('admin.users');
    }

    /**
     * Fetch all users (for admin panel)
     */
    public function fetchAllUsers(Request $request)
    {
        $users = User::where('is_admin', 0)->get(); // ✅ skip admins

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all users successfully',
            'users' => $users
        ], 200);
    }

    public function showTrainerPage()
    {
        // just return the blade view — no data directly passed
        return view('admin.trainers');
    }

    /**
     * Fetch all trainers (for admin panel)
     */
    public function fetchAllTrainers(Request $request)
    {
        $trainers = Trainer::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all trainers successfully',
            'trainers' => $trainers
        ], 200);
    }

    public function showCoursePage()
    {
        return view('admin.courses');
    }

    // ✅ Fetch all courses (AJAX)
    public function fetchAllCourses(Request $request)
    {
        $courses = Course::with('trainer:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched all courses successfully',
            'courses' => $courses
        ], 200);
    }

}


