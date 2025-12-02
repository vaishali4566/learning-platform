<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * User View Page
     */
    public function showUserPage()
    {
        return view('admin.users');
    }

    /**
     * Fetch all users
     */
    public function fetchAllUsers()
    {
        $users = User::where('is_admin', 0)->get();

        return response()->json([
            'status' => 'success',
            'users' => $users
        ]);
    }

    /**
     * Add user
     */
    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'country'=>'nullable|string|max:100'
        ]);

        User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>bcrypt($validated['password']),
            'country'=>$validated['country'] ?? null,
        ]);

        return response()->json(['status'=>'success','message'=>'User added']);
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['status'=>'error','message'=>'Not found']);

        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email,' .$user->id,
            'country'=>'nullable|string|max:100',
        ]);

        $user->update($validated);
        return response()->json(['status'=>'success','message'=>'Updated']);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) 
            return response()->json(['status'=>'error','message'=>'Not found']);

        $user->delete();

        return response()->json(['status'=>'success']);
    }
}
