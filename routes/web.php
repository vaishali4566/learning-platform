<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Trainer\QuizController;

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


Route::prefix('trainer')->name('trainer.')->group(function() {
    Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('quizzes/store', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    
    // Add question to a quiz
    Route::post('quizzes/{quiz}/questions/store', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    
    // Finalize quiz (calculate total & passing marks)
    Route::post('quizzes/{quiz}/finalize', [QuizController::class, 'finalizeQuiz'])->name('quizzes.finalize');
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
    Route::get('/', [CoursesController::class, 'showCreateForm'])->name('courses.create');
    Route::post('/', [CoursesController::class, 'create'])->name('courses.store');
});

Route::group(['prefix' => 'lessons'], function () {
    Route::get('/', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.store');
});
