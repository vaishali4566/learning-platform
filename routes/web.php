<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TrainerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Trainer\QuizController;
use App\Http\Controllers\Web\UserQuizController;
use Illuminate\Support\Facades\Auth;

// --------------------------------------------------
// Root redirect
// --------------------------------------------------
Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('user.dashboard');
    } elseif (Auth::guard('trainer')->check()) {
        return redirect()->route('trainer.dashboard');
    }
    return redirect()->route('user.login');
});

// --------------------------------------------------
// 🧑 USER AUTH (Web)
// --------------------------------------------------
Route::prefix('user')->group(function () {

    // Guest routes
    Route::middleware(['guest:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.submit');
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    });

    // Authenticated routes
    Route::middleware(['authenticate.user:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.dashboard');
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

    // Guest routes
    Route::middleware(['guest:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
        Route::get('/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
        Route::post('/login', [AuthController::class, 'trainerLogin'])->name('trainer.login.submit');
        Route::post('/register', [AuthController::class, 'trainerRegister'])->name('trainer.register.submit');
    });

    // Authenticated routes
    Route::middleware(['authenticate.user:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/', [TrainerController::class, 'index'])->name('trainer.dashboard');
        Route::get('/profile', [TrainerController::class, 'profile'])->name('trainer.profile');
        Route::post('/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');
    });
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


// Submit email to send reset link
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Show reset password form (the link in email will use this route)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset');// important: this name must be "password.reset"

// Submit new password
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::prefix('user')->middleware(['authenticate.user:web'])->group(function() {
    Route::get('/quizzes', [UserQuizController::class, 'index'])->name('user.quizzes.index');
    Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
});




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
    Route::get('/{courseId}/explore', [CoursesController::class, 'showExplorePage'])->name('courses.exploreNow');
    Route::get('/{id}', [CoursesController::class, 'getCourse']);
});

Route::group(['prefix' => 'lessons'], function () {
    Route::get('/lessonform', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.create');
    Route::get('/view/{id}',[LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('all/{id}',[LessonsController::class, 'viewLesson1'])->name('lessons.alllesson');
    Route::get('/{id}/stream',[LessonsController::class, 'stream']);   
});
