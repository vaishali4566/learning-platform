<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'old_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // ✅ FIRST validate
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ THEN check old password
        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors([
                    'old_password' => 'Your old password is incorrect.'
                ])->withInput();
            }
        }

        // ✅ Upload image if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // ✅ Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
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
            return redirect()->route('admin.login')->with('success', 'Account deleted successfully.');
        }

        return back()->with('error', 'Error deleting account.');
    }
}
