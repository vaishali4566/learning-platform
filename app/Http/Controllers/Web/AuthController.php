<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Trainer;

class AuthController extends Controller
{
    // ================== USER WEB AUTH ==================
    public function showUserLoginForm()
    {
        return view('user.auth.login');
    }

    public function showUserRegisterForm()
    {
        return view('user.auth.register');
    }

    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);
        return redirect()->route('user.dashboard')->with('success', 'User registered successfully!');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('user.login');
    }

    // ================== TRAINER WEB AUTH ==================
    public function showTrainerLoginForm()
    {
        return view('trainer.auth.login');
    }

    public function showTrainerRegisterForm()
    {
        return view('trainer.auth.register');
    }

    public function trainerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('trainer')->login($trainer);
        return redirect()->route('trainer.dashboard')->with('success', 'Trainer registered successfully!');
    }

    public function trainerLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('trainer')->attempt($credentials)) {
            return redirect()->route('trainer.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function trainerLogout()
    {
        Auth::guard('trainer')->logout();
        return redirect()->route('trainer.login');
    }
}
