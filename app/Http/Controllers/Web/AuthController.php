<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Trainer;

class AuthController extends Controller
{
    // =========================================================
    // ======================= USER AUTH ======================
    // =========================================================

    // Show login form
    public function showUserLoginForm()
    {
        return view('user.auth.login');
    }

    // Show registration form
    public function showUserRegisterForm()
    {
        return view('user.auth.register');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard'); // create this blade view
    }

    // Handle user registration
    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('user_profile_images', 'public');
            $user->profile_image = $path;
            $user->save();
        }

        Auth::guard('web')->login($user);

        return response()->json([
            'success' => true,
            'redirect' => route('user.dashboard'),
            'message' => 'User registered successfully!'
        ]);
    }

    // Handle user login
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            if ($user->is_admin) {
                // If user is admin, redirect to admin dashboard
                return response()->json([
                    'success' => true,
                    'redirect' => route('admin.dashboard')
                ]);
            } else {
                // Normal user
                return response()->json([
                    'success' => true,
                    'redirect' => route('user.dashboard')
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'errors' => ['password' => ['Incorrect email or password.']]
        ], 422);
    }


    // Handle user logout
    public function userLogout()
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken(); 
        return redirect()->route('user.login');
    }

    // Show user forgot password form
    public function showUserForgotPasswordForm()
    {
        return view('user.auth.forgot-password');
    }

    // Handle user sending reset email
   public function forgotPassword(Request $request)
    {
        // 1. Validate the email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // 2. Attempt to send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Check result and return back with proper message
        if ($status === Password::RESET_LINK_SENT) {
            // Success: redirect back with a session success message
            return redirect()->back()->with('status', __($status));
        } else {
            // Failure: redirect back with error
            return redirect()->back()->withErrors(['email' => __($status)]);
        }
    }


    // Show user reset password form
    public function showResetPasswordForm(Request $request, $token)
    {
        $email = $request->query('email');

        return view('user.auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Handle user reset password submission
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

    // =========================================================
    // ===================== TRAINER AUTH =====================
    // =========================================================

    // Show trainer login form
    public function showTrainerLoginForm()
    {
        return view('trainer.auth.login');
    }

    // Show trainer registration form
    public function showTrainerRegisterForm()
    {
        return view('trainer.auth.register');
    }

    // Handle trainer registration
    public function trainerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('trainer_profile_images', 'public');
            $trainer->profile_image = $path;
            $trainer->save();
        }

        Auth::guard('trainer')->login($trainer);

        return response()->json([
            'success' => true,
            'redirect' => route('trainer.dashboard'),
            'message' => 'Trainer registered successfully!'
        ]);
    }

    // Handle trainer login
    public function trainerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('trainer')->attempt($credentials)) {
            return response()->json([
                'success' => true,
                'redirect' => route('trainer.dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => ['password' => ['Incorrect email or password.']]
        ], 422);
    }

    // Handle trainer logout
    public function trainerLogout()
    {
        Auth::guard('trainer')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken(); 
        return redirect()->route('trainer.login');
    }

    // Show trainer forgot password form
    public function showTrainerForgotPasswordForm()
    {
        return view('trainer.auth.forgot-password');
    }

    // Handle sending trainer reset email
    public function forgotTrainerPassword(Request $request)
    {
        // 1. Validate the email
        $request->validate([
            'email' => 'required|email|exists:trainers,email',
        ]);

        // 2. Attempt to send the password reset link using trainer broker
        $status = Password::broker('trainers')->sendResetLink(
            $request->only('email')
        );

        // 3. Check result and return back with proper message
        if ($status === Password::RESET_LINK_SENT) {
            // Success: redirect back with a session success message
            return redirect()->back()->with('status', __($status));
        } else {
            // Failure: redirect back with error
            return redirect()->back()->withErrors(['email' => __($status)]);
        }
    }


    // Show trainer reset password form
    public function showTrainerResetPasswordForm(Request $request, $token)
    {
        $email = $request->query('email');

        return view('trainer.auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Handle trainer reset password submission
    public function resetTrainerPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:trainers,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::broker('trainers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($trainer, $password) {
                $trainer->password = Hash::make($password);
                $trainer->setRememberToken(Str::random(60));
                $trainer->save();
                event(new PasswordReset($trainer));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('trainer.login')->with('success', 'Password reset successful. You can now login.');
        } else {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }
    }
}
