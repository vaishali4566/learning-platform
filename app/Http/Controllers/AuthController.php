<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Trainer;


class AuthController extends Controller
{
    //
    //User authentications
    public function userLogin(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Fetch user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // 3. Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password'
            ], 401);
        }

        // 4. Successful login response
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user
        ], 200);
    }

    public function userRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation field
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // optional image upload
        ]);

        // Return validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle profile image upload if exists
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'city' => $request->city,
            'country' => $request->country,
            'profile_image' => $profileImagePath,
        ]);

        // Return success response
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

    //Trainer authentications
    public function trainerRegister(Request $request)
    {
        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // optional image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2️⃣ Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('trainer_profile_images', 'public');
        }

        // 3️⃣ Create trainer record
        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'city' => $request->city,
            'country' => $request->country,
            'profile_image' => $profileImagePath,
        ]);

        // 4️⃣ Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Trainer registered successfully',
            'trainer' => $trainer
        ], 201);
    }

    public function trainerLogin(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2️⃣ Fetch trainer by email
        $trainer = Trainer::where('email', $request->email)->first();

        if (!$trainer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trainer not found'
            ], 404);
        }

        // 3️⃣ Verify password
        if (!Hash::check($request->password, $trainer->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password'
            ], 401);
        }

        // 4️⃣ Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Trainer logged in successfully',
            'trainer' => $trainer
        ], 200);
    }

    
    public function updateTrainer(Request $request)
    {
        return "Update trainer successfully (dummy response).";
    }

    //common auth for both user and trainer
    public function resetPassword(Request $request)
    {
        return "reset password success";
    }
}
