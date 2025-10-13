<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateProfile(Request $request){
        $user = Auth::user();

        $validated = $request->validate([
            'bio' =>'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:2048',
            'city' =>'nullable|string|max:100',
            'country' =>'nullable|string|max:100'
        ]);


        if($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully.', 'user'=> $user], 200);
    }
}
