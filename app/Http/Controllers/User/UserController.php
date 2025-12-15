<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment; 
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show logged-in user profile
     */
    public function profile()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Please login first.');
        }

        return view('user.profile', compact('user'));
    }


    public function index()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::guard('web')->user();
        return view('user.dashboard', compact('user'));
    }

    /**
     * Update user profile details
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Upload image if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // ✅ Update user info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->city = $request->city;
        $user->country = $request->country;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        if ($user->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('user.login')->with('success', 'Your account has been successfully deleted.');
        }

        return back()->with('error', 'There was an error deleting your account.');
    }
    public function purchaseHistory()
    {
        $userId = Auth::id(); // logged-in user

        $payments = Payment::with(['course', 'trainer'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.payment.purchase-history', compact('payments'));

    }
}
