<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// USER WEB
Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
Route::post('/login', [AuthController::class, 'userLogin']);
Route::post('/register', [AuthController::class, 'userRegister']);
Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');
Route::get('/user/dashboard', function () {
    return view('user.dashboard'); // create this Blade
})->name('user.dashboard');

// TRAINER WEB
Route::get('/trainer/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
Route::get('/trainer/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
Route::post('/trainer/login', [AuthController::class, 'trainerLogin']);
Route::post('/trainer/register', [AuthController::class, 'trainerRegister']);
Route::post('/trainer/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');

Route::get('/user/all', [AdminController::class, 'fetchAllUsers']);
Route::get('/trainer/all', [AdminController::class, 'fetchAllTrainers']);

//payment route
Route::get('/payment' , [PaymentController :: class , "base"]);
Route::controller(PaymentController::class)->group(function () {
    Route::get('/stripe', 'stripe');
    Route::post('/stripe', 'stripePost')->name('stripe.post');
});
Route::get('/profile', function () {
    return view('user.profile');
});