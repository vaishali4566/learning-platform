<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Trainer;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        // ✅ Create token for API
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }



    public function userRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'city' => $request->city,
            'country' => $request->country,
            'profile_image' => $profileImagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function updateUser(Request $request)
    {
        return "Update user successfully.";
    }

    public function trainerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6|confirmed',
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('trainer_profile_images', 'public');
        }

        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'city' => $request->city,
            'country' => $request->country,
            'profile_image' => $profileImagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer registered successfully',
            'trainer' => $trainer
        ], 201);
    }

    public function trainerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $trainer = Trainer::where('email', $request->email)->first();

        if (!$trainer || !Hash::check($request->password, $trainer->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ])->withInput();
        }

        // ✅ Log in trainer into session
        Auth::guard('trainer')->login($trainer);

        // Optional: regenerate session
        $request->session()->regenerate();

        return redirect()->route('trainer.dashboard');
    }


    public function updateTrainer(Request $request)
    {
        return "Update trainer successfully.";
    }


    public function trainerLogout(Request $request)
    {
        Auth::guard('trainer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trainer.login')->with('success', 'Logged out successfully');
    }
}
