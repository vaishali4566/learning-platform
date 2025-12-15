<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Trainer;

class TrainerController extends Controller
{
    /**
     * Show logged-in trainer profile
     */
    public function profile()
    {
        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            return redirect()->route('trainer.login')->with('error', 'Please login first.');
        }

        return view('trainer.profile', compact('trainer'));
    }

    /**
     * Show trainer dashboard
     */
    public function index()
    { 
        
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.login');
        }

        $trainer = Auth::guard('trainer')->user();
        return view('trainer.dashboard', compact('trainer'));
    }

    /**
     * Update trainer profile details
     */
    public function updateProfile(Request $request)
    {
            /** @var \App\Models\Trainer $trainer */

        $trainer = Auth::guard('trainer')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'bio' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'old_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Upload image if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('trainer_profile_images', 'public');
            $trainer->profile_image = $path;
        }

         // ✅ THEN check old password
        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $trainer->password)) {
                return back()->withErrors([
                    'old_password' => 'Your old password is incorrect.'
                ])->withInput();
            }
        }

        // ✅ Upload image if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $trainer->profile_image = $path;
        }

        // Update trainer info
        $trainer->name = $request->name;
        $trainer->email = $request->email;
        $trainer->bio = $request->bio;
        $trainer->qualification = $request->qualification;
        $trainer->experience_years = $request->experience_years;
        $trainer->specialization = $request->specialization;
        $trainer->city = $request->city;
        $trainer->country = $request->country;

        if ($request->filled('password')) {
            $trainer->password = Hash::make($request->password);
        }

        $trainer->save();

        return redirect()->route('trainer.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete trainer account
     */
    public function deleteAccount(Request $request)
    {
        /** @var \App\Models\Trainer $trainer */

        $trainer = Auth::guard('trainer')->user();

        Auth::guard('trainer')->logout();

        if ($trainer->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('trainer.login')->with('success', 'Your account has been successfully deleted.');
        }

        return back()->with('error', 'There was an error deleting your account.');
    }

    // Web/TrainerController.php

    public function addEarning($trainerId, $courseId, $amount, $source = 'course sale')
    {
        \App\Models\TrainerEarning::create([
            'trainer_id' => $trainerId,
            'course_id' => $courseId,
            'amount' => $amount,
            'source' => $source,
        ]);
    }

    public function totalEarnings($trainerId)
    {
        return \App\Models\TrainerEarning::where('trainer_id', $trainerId)->sum('amount');
    }

}
