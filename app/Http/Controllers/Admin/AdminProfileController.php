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

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully'
        ]);
    }
    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'country' => 'nullable|string|max:100',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'country' => $validated['country'] ?? null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'User added successfully']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found']);
        }

        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
    }
    // ✅ Fetch all trainers
    public function fetchTrainers()
    {
        $trainers = Trainer::select('id', 'name', 'email', 'qualification', 'specialization', 'country', 'profile_image')->get();

        return response()->json([
            'status' => 'success',
            'trainers' => $trainers
        ]);
    }

    // ✅ Add a new trainer
    public function addTrainer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        Trainer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'qualification' => $validated['qualification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'country' => $validated['country'] ?? null,
            'approved' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer added successfully'
        ]);
    }

    // ✅ Update trainer details
    public function updateTrainer(Request $request, $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trainer not found'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:trainers,email,' . $trainer->id,
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        $trainer->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer updated successfully'
        ]);
    }

    // ✅ Delete trainer
    public function deleteTrainer($id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trainer not found'
            ]);
        }

        $trainer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer deleted successfully'
        ]);
    }
    

    // ✅ Approve or Reject a course
    public function updateStatus(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $course->status = $request->status;
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Course status updated successfully.',
            'status' => $course->status,
        ]);
    }

    // ✅ Delete a course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Optionally delete related lessons, files, etc.
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }
}




