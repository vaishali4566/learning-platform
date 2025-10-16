<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
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
    Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
    Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
    Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
    Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.submit');
    Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');

    // Dashboard (protected)
    Route::middleware('auth')->group(function () {
        Route::view('/dashboard', 'user.dashboard')->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('user.update');
        Route::post('/delete', [UserController::class, 'deleteAccount'])->name('user.delete');
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

Route::group(['prefix'=>'courses'], function(){
    Route::get('/courseform', [CoursesController::class, 'showCreateForm'])->name('courses.create');
    Route::post('/', [CoursesController::class, 'create']);
    Route::get('/', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/all',[CoursesController::class, 'getAllCourse']);
    Route::get('/view/{id}',[CoursesController::class, 'showPage'])->name('courses.show');
    Route::put('/{id}', [CoursesController::class, 'update']);
    Route::delete('/{id}', [CoursesController::class, 'delete']);
    Route::get('/{id}/lessons', [LessonsController::class, 'lessonsByCourse']);
    Route::get('/mycourses', [CoursesController::class, 'myCourses'])->name('courses.mycourses');
    Route::get('/{id}', [CoursesController::class, 'getCourse']);
});

Route::group(['prefix' => 'lessons'], function () {
    Route::get('/lessonform', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.create');
    Route::get('/view/{id}',[LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('all/{id}',[LessonsController::class, 'viewLesson1'])->name('lessons.alllesson');
    Route::get('/{id}',[LessonsController::class, 'stream']);   
});
