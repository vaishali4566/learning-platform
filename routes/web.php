<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TrainerController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Trainer\QuizController;
use App\Http\Controllers\Web\UserQuizController;

// =========================================================
// ======================= ROOT REDIRECT ==================
// =========================================================
Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('user.dashboard');
    } elseif (Auth::guard('trainer')->check()) {
        return redirect()->route('trainer.dashboard');
    }
    return redirect()->route('user.login');
});

// =========================================================
// ======================= USER AUTH ======================
// =========================================================
Route::prefix('user')->group(function () {

    // Guest routes (login/register/forgot password)
    Route::middleware(['guest:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.submit');
        Route::get('/forgot-password', [AuthController::class, 'showUserForgotPasswordForm'])->name('user.password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('user.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('user.password.update');
    });

    // Authenticated routes (dashboard/profile/logout)
    Route::middleware(['authenticate.user:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('user.update');
        Route::post('/delete', [UserController::class, 'deleteAccount'])->name('user.delete');
        Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');

        // User quizzes
        Route::get('/quizzes', [UserQuizController::class, 'index'])->name('user.quizzes.index');
        Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
        Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
    });
});

// =========================================================
// ===================== TRAINER AUTH =====================
// =========================================================
Route::prefix('trainer')->group(function () {

    // Guest routes (login/register/forgot password)
    Route::middleware(['guest:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
        Route::get('/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
        Route::post('/login', [AuthController::class, 'trainerLogin'])->name('trainer.login.submit');
        Route::post('/register', [AuthController::class, 'trainerRegister'])->name('trainer.register.submit');
        Route::get('/forgot-password', [AuthController::class, 'showTrainerForgotPasswordForm'])->name('trainer.password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotTrainerPassword'])->name('trainer.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showTrainerResetPasswordForm'])->name('trainer.password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetTrainerPassword'])->name('trainer.password.update');
    });

    // Authenticated routes (dashboard/profile/logout)
    Route::middleware(['authenticate.user:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/', [TrainerController::class, 'index'])->name('trainer.dashboard');
        Route::get('/profile', [TrainerController::class, 'profile'])->name('trainer.profile');
        Route::post('/update', [TrainerController::class, 'updateProfile'])->name('trainer.update');
        Route::post('/delete', [TrainerController::class, 'deleteAccount'])->name('trainer.delete');
        Route::post('/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');

        // Trainer quizzes CRUD
        Route::get('quizzes', [QuizController::class, 'index'])->name('trainer.quizzes.index');
        Route::get('quizzes/create', [QuizController::class, 'create'])->name('trainer.quizzes.create');
        Route::post('quizzes/store', [QuizController::class, 'store'])->name('trainer.quizzes.store');
        Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('trainer.quizzes.edit');
        Route::post('quizzes/{quiz}/questions/store', [QuizController::class, 'storeQuestion'])->name('trainer.quizzes.questions.store');
        Route::post('quizzes/{quiz}/finalize', [QuizController::class, 'finalizeQuiz'])->name('trainer.quizzes.finalize');
    });
});

// =========================================================
// ======================== ADMIN =========================
// =========================================================
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'fetchAllUsers'])->name('admin.users');
    Route::get('/trainers', [AdminController::class, 'fetchAllTrainers'])->name('admin.trainers');
});

// =========================================================
// ======================== PAYMENT =======================
// =========================================================
Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::get('/', 'base')->name('payment.base');
    Route::get('/stripe', 'stripe')->name('payment.stripe');
    Route::post('/stripe', 'stripePost')->name('stripe.post');
});

// =========================================================
// ======================= COURSES ========================
// =========================================================
Route::prefix('courses')->group(function () {
    Route::get('/', [CoursesController::class, 'showCreateForm'])->name('courses.create');
    Route::post('/', [CoursesController::class, 'create'])->name('courses.store');
});

// =========================================================
// ======================= LESSONS ========================
// =========================================================
Route::prefix('lessons')->group(function () {
    Route::get('/', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.store');
});
