<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\DB;

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

    public function showForgotPasswordForm()
    {
        return view('user.auth.forgot-password');
    }
    public function userRegister(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('user_profile_images', 'public');
            $user->profile_image = $path; // store path in DB
            $user->save(); // <- important
        }


        // Log in the user
        Auth::guard('web')->login($user);

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'redirect' => route('user.dashboard'),
            'message' => 'User registered successfully!'
        ]);
    }


    public function userLogin(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'success' => true,
                'redirect' => route('user.dashboard')
            ]);
        }

        // Login failed
        return response()->json([
            'success' => false,
            'errors' => [
                'password' => ['Incorrect email or password.']
            ]
        ], 422);
    }



    public function userLogout()
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken(); 
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
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create trainer
        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('trainer_profile_images', 'public');
            $trainer->profile_image = $path; // save path in DB
            $trainer->save();
        }

        // Login the trainer
        Auth::guard('trainer')->login($trainer);

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'redirect' => route('trainer.dashboard'),
            'message' => 'Trainer registered successfully!'
        ]);
    }



    public function trainerLogin(Request $request)
    {
        // Validate input first
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::guard('trainer')->attempt($credentials)) {
            // Return JSON response for AJAX
            return response()->json([
                'success' => true,
                'redirect' => route('trainer.dashboard')
            ]);
        }

        // Login failed - send error JSON
        $errorMessage = ['password' => ['Incorrect email or password.']];

        return response()->json([
            'success' => false,
            'errors' => $errorMessage
        ], 422); // 422 triggers AJAX error callback
    }


    public function trainerLogout()
    {
        Auth::guard('trainer')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken(); 
        return redirect()->route('trainer.login');
    }

    // ================== PASSWORD RESET ==================
    
    // Handle sending reset email
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Reset password link sent to your email.');
        } else {
            return back()->with('error', 'Unable to send reset link. Please try again.');
        }
    }

    // Show reset password form
    public function showResetPasswordForm(Request $request, $token)
    {
        // Get email from query string
        $email = $request->query('email');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }


    // Handle reset password submission
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('user.login')->with('success', 'Password reset successful. You can now login.');
        } else {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }
    }
}
