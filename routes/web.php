<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ----------------------------
// Web Controllers
// ----------------------------
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TrainerController;
use App\Http\Controllers\Web\UserQuizController;

// ----------------------------
// Core Controllers
// ----------------------------
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\TelegramController;

// ----------------------------
// Trainer Controllers
// ----------------------------
use App\Http\Controllers\Trainer\QuizController;
use App\Http\Controllers\Trainer\TrainerCourseController;
use App\Http\Controllers\Trainer\ReportController;

// ----------------------------
// Admin Controllers
// ----------------------------
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Trainer\TrainerLessonController;

// ======================================================================
// ROOT REDIRECT
// ======================================================================
Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }

    if (Auth::guard('trainer')->check()) {
        return redirect()->route('trainer.dashboard');
    }

    return redirect()->route('user.login');
});


// ======================================================================
// USER AUTH ROUTES
// ======================================================================
Route::prefix('user')->group(function () {
    // Guest routes
    Route::middleware(['guest:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.submit');

        // Forgot & Reset Password
        Route::get('/forgot-password', [AuthController::class, 'showUserForgotPasswordForm'])->name('user.password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('user.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('user.password.update');
    });

    // Authenticated routes
    Route::middleware(['authenticate.user:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('user.update');
        Route::post('/delete', [UserController::class, 'deleteAccount'])->name('user.delete');
        Route::post('/logout', [AuthController::class, 'userLogout'])->name('user.logout');

        // Quizzes
        Route::get('/quizzes', [UserQuizController::class, 'index'])->name('user.quizzes.index');
        Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
        Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
        Route::get('/quizzes/{quiz}/result', [UserQuizController::class, 'result'])->name('user.quizzes.result');


        // Payments
        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('/{courseId}', 'stripe')->name('payment.stripe');
            Route::post('/', 'stripePost')->name('payment.post');
        });
    });
});

// ======================================================================
// TRAINER AUTH ROUTES
// ======================================================================
Route::prefix('trainer')->group(function () {
    // Guest routes
    Route::middleware(['guest:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showTrainerLoginForm'])->name('trainer.login');
        Route::get('/register', [AuthController::class, 'showTrainerRegisterForm'])->name('trainer.register');
        Route::post('/login', [AuthController::class, 'trainerLogin'])->name('trainer.login.submit');
        Route::post('/register', [AuthController::class, 'trainerRegister'])->name('trainer.register.submit');

        // Forgot & Reset Password
        Route::get('/forgot-password', [AuthController::class, 'showTrainerForgotPasswordForm'])->name('trainer.password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotTrainerPassword'])->name('trainer.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showTrainerResetPasswordForm'])->name('trainer.password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetTrainerPassword'])->name('trainer.password.update');
    });

    // Authenticated routes
    Route::middleware(['authenticate.user:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/', [TrainerController::class, 'index'])->name('trainer.dashboard');
        Route::get('/profile', [TrainerController::class, 'profile'])->name('trainer.profile');
        Route::post('/update', [TrainerController::class, 'updateProfile'])->name('trainer.update');
        Route::post('/delete', [TrainerController::class, 'deleteAccount'])->name('trainer.delete');
        Route::post('/logout', [AuthController::class, 'trainerLogout'])->name('trainer.logout');
        Route::get('/report', [ReportController::class, 'index'])->name('trainer.report');

        // Courses
        Route::prefix('courses')->name('trainer.courses.')->group(function () {
            Route::get('/', [TrainerCourseController::class, 'index'])->name('index');
            Route::get('/create', [TrainerCourseController::class, 'create'])->name('create');
            Route::post('/', [TrainerCourseController::class, 'store'])->name('store');
            Route::get('/my', [TrainerCourseController::class, 'myCourses'])->name('my');
            Route::get('/explore/{courseId}', [TrainerCourseController::class, 'explore'])->name('explore');
            Route::delete('/{course}', [TrainerCourseController::class, 'destroy'])->name('destroy');

            Route::get('/{course}/lessons', [TrainerLessonController::class, 'manage'])->name('lessons.manage');
            Route::get('/{course}/lessons/create', [TrainerLessonController::class, 'create'])->name('lessons.create');
            Route::post('/{course}/lessons', [TrainerLessonController::class, 'store'])->name('lessons.store');
            Route::get('/{course}/lessons/view', [TrainerLessonController::class, 'viewLesson1'])->name('lessons.view');
            
        });

        


        // Quizzes
        Route::prefix('quizzes')->group(function () {
            Route::get('/', [QuizController::class, 'index'])->name('trainer.quizzes.index');
            Route::get('/{id}/questions', [QuizController::class, 'showQuestions'])->name('trainer.quizzes.questions');
            Route::get('/create', [QuizController::class, 'create'])->name('trainer.quizzes.create');
            Route::post('/store', [QuizController::class, 'store'])->name('trainer.quizzes.store');
            Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('trainer.quizzes.edit');
            Route::post('/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('trainer.quizzes.questions.store');
            Route::delete('/questions/{question}', [QuizController::class, 'deleteQuestion'])->name('trainer.quizzes.questions.delete');
            Route::post('/{quiz}/finalize', [QuizController::class, 'finalizeQuiz'])->name('trainer.quizzes.finalize');
        });

    });
});



// ======================================================================
// ADMIN ROUTES (Single Controller)
// ======================================================================
Route::prefix('admin')->middleware(['authenticate.user:web', 'admin.only', 'prevent.back.history:web'])->group(function () {
    // Dashboard & Profile
    Route::get('/', [AdminProfileController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.update');
    Route::post('/account/delete', [AdminProfileController::class, 'deleteAccount'])->name('admin.account.delete');

    // Data Management
    Route::get('/users', [AdminProfileController::class, 'showUserPage'])->name('admin.users');
    Route::get('/users/fetch', [AdminProfileController::class, 'fetchAllUsers'])->name('admin.users.fetch');
    
    
    Route::post('/users/update/{id}', [AdminProfileController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/users/add', [AdminProfileController::class, 'addUser'])->name('admin.users.add');
    Route::delete('/users/delete/{id}', [AdminProfileController::class, 'deleteUser'])->name('admin.users.delete');

    // Trainer Management Routes
    Route::prefix('trainers')->group(function () {
        Route::get('/', [AdminProfileController::class, 'showTrainerPage'])->name('admin.trainers');
        Route::get('/fetch', [AdminProfileController::class, 'fetchAllTrainers'])->name('admin.trainers.fetch');
        Route::post('/add', [AdminProfileController::class, 'addTrainer'])->name('admin.trainers.add');
        Route::post('/update/{id}', [AdminProfileController::class, 'updateTrainer'])->name('admin.trainers.update');
        Route::delete('/delete/{id}', [AdminProfileController::class, 'deleteTrainer'])->name('admin.trainers.delete');
    });

    // Course Management Routes
    Route::prefix('courses')->group(function () {
        Route::get('/', [AdminProfileController::class, 'showCoursePage'])->name('admin.courses');
        Route::get('/fetch', [AdminProfileController::class, 'fetchAllCourses'])->name('admin.courses.fetch');
        Route::post('/courses/update-status/{id}', [AdminProfileController::class, 'updateStatus'])->name('admin.courses.updateStatus');
        Route::delete('/courses/{id}', [AdminProfileController::class, 'destroy'])->name('admin.courses.destroy');
    });

    // Optional
    
    Route::get('/quizzes', [AdminProfileController::class, 'fetchAllQuizzes'])->name('admin.quizzes.index')->middleware('optional');
    Route::get('/reports', [AdminProfileController::class, 'reports'])->name('admin.reports')->middleware('optional');
    Route::get('/settings', [AdminProfileController::class, 'settings'])->name('admin.settings')->middleware('optional');

    // Logout
    Route::post('/logout', [AuthController::class, 'userLogout'])->name('admin.logout');
});


// ======================================================================
// COURSES ROUTES
// ======================================================================
Route::group(['prefix' => 'courses'], function () {
    Route::post('/', [CoursesController::class, 'store']);
    Route::get('/create', [CoursesController::class, 'create'])->name('courses.create');
    Route::delete('/{id}', [CoursesController::class, 'delete']);
    Route::get('/trainer', [CoursesController::class, 'showTrainerCourses'])->name('courses.trainercourses');
    Route::put('/trainer/{id}', [CoursesController::class, 'update'])->name('courses.update');
    Route::get('/trainer/course/count', [CoursesController::class, 'coursesWithPurchaseCount'])->name('course.purchase');
    Route::get('/data', [CoursesController::class, 'getAll']);
    Route::get('/', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/{id}/lessons', [LessonsController::class, 'lessonsByCourse']);
    Route::get('/my', [CoursesController::class, 'myCourses'])->name('courses.mycourses');
    Route::get('/{courseId}/explore', [CoursesController::class, 'explore'])->name('courses.explore');
    Route::get('/{id}', [CoursesController::class, 'getCourse']);
});


// ======================================================================
// LESSONS ROUTES
// ======================================================================
Route::group(['prefix' => 'lessons'], function () {
    Route::get('/lessonform', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.create');
    Route::get('/view/{id}', [LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('all/{id}', [LessonsController::class, 'viewLesson1'])->name('lessons.alllesson');
    Route::get('/{id}', [LessonsController::class, 'stream']);
});


// ======================================================================
// CHATBOT & TELEGRAM
// ======================================================================
Route::post('/chatbot/send', [ChatBotController::class, 'send'])->name('chatbot.send');
Route::post('/send-to-telegram', [TelegramController::class, 'sendMessage'])->name('send.to.telegram');


// ======================================================================
// MISCELLANEOUS
// ======================================================================
Route::get('/contact', function () {
    return view('contact');
});

// 🔔 Universal Notifications Routes
Route::prefix('notifications')->group(function () {
    Route::post('/create', [NotificationController::class, 'store'])->name('notifications.create');
    Route::get('/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
