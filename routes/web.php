<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Home Page
Route::view('/', 'welcome')->name('home');

// --------------------------------------------------
// 🧑 USER AUTH (Web)
// --------------------------------------------------
Route::prefix('user')->group(function () {
    // Authentication (Login & Register)
    Route::middleware(['guest:web'])->group(function () {
        Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.submit');
        
    });

    // Dashboard (protected)
    Route::middleware('auth.user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('user.update');
        Route::post('/delete', [UserController::class, 'deleteAccount'])->name('user.delete');
        Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');
    });
});

// --------------------------------------------------
// 🧑‍🏫 TRAINER AUTH (Web)
// --------------------------------------------------
Route::prefix('trainer')->group(function () {
    Route::get('/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
    Route::get('/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
    Route::post('/login', [AuthController::class, 'trainerLogin'])->name('trainer.login.submit');
    Route::post('/register', [AuthController::class, 'trainerRegister'])->name('trainer.register.submit');
    Route::post('/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');
});

// --------------------------------------------------
// ⚙️ ADMIN ROUTES
// --------------------------------------------------
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'fetchAllUsers'])->name('admin.users');
    Route::get('/trainers', [AdminController::class, 'fetchAllTrainers'])->name('admin.trainers');
});

// --------------------------------------------------
// 💳 PAYMENT ROUTES
// --------------------------------------------------
Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::get('/', 'base')->name('payment.base');
    Route::get('/stripe', 'stripe')->name('payment.stripe');
    Route::post('/stripe', 'stripePost')->name('stripe.post');
});

//////////////////////////
// FORGOT & RESET PASSWORD ROUTES
//////////////////////////

// Show forgot password form
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Submit email to send reset link
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Show reset password form (the link in email will use this route)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset');// important: this name must be "password.reset"

// Submit new password
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');








