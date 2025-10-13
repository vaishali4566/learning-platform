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

    // ================== PASSWORD RESET ==================
    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

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
