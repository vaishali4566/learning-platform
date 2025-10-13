<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//////////////////////////
// USER WEB ROUTES
//////////////////////////
Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
Route::post('/login', [AuthController::class, 'userLogin']);
Route::post('/register', [AuthController::class, 'userRegister']);
Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');

Route::get('/user/dashboard', function () {
    return view('user.dashboard'); // create this Blade
})->name('user.dashboard');

//////////////////////////
// TRAINER WEB ROUTES
//////////////////////////
Route::get('/trainer/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
Route::get('/trainer/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
Route::post('/trainer/login', [AuthController::class, 'trainerLogin']);
Route::post('/trainer/register', [AuthController::class, 'trainerRegister']);
Route::post('/trainer/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');

//////////////////////////
// ADMIN ROUTES
//////////////////////////
Route::get('/user/all', [AdminController::class, 'fetchAllUsers']);
Route::get('/trainer/all', [AdminController::class, 'fetchAllTrainers']);

//////////////////////////
// PAYMENT ROUTES
//////////////////////////
Route::get('/payment', [PaymentController::class, 'base']);
Route::controller(PaymentController::class)->group(function () {
    Route::get('/stripe', 'stripe');
    Route::post('/stripe', 'stripePost')->name('stripe.post');
});

//////////////////////////
// PROFILE ROUTE
//////////////////////////
Route::get('/profile', function () {
    return view('user.profile');
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
