<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use HotwiredLaravel\TurboLaravel\Http\Middleware\TurboMiddleware;
// ----------------------------
// USER CONTROLLERS
// ----------------------------
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserQuizController;
use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\User\UserLessonController;
use App\Http\Controllers\User\UserPracticeTestController;

// ----------------------------
// TRAINER CONTROLLERS
// ----------------------------
use App\Http\Controllers\Trainer\TrainerController;
use App\Http\Controllers\Trainer\QuizController;
use App\Http\Controllers\Trainer\TrainerCourseController;
use App\Http\Controllers\Trainer\ReportController;
use App\Http\Controllers\Trainer\TrainerStudentController;
use App\Http\Controllers\Trainer\TrainerLessonController;

// ----------------------------
// ADMIN CONTROLLERS
// ----------------------------
use App\Http\Controllers\Admin\AdminProfileController;

// ----------------------------
// CORE & MISC CONTROLLERS
// ----------------------------
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CourseFeedbackController;
use App\Http\Controllers\PracticeTestController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\ChatRequestController;

// ======================================================================
// ROOT REDIRECT
// ======================================================================
Route::get('/', function () {
    if (Auth::guard('trainer')->check()) return redirect()->route('trainer.dashboard');
    if (Auth::guard('web')->check()) {
        return Auth::guard('web')->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('user.login');
});

// ======================================================================
// USER ROUTES
// ======================================================================
Route::prefix('user')->name('user.')->group(function () {

    // ---------------- Guest Routes ----------------
    Route::middleware(['guest:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('login');
        Route::get('/register', [AuthController::class, 'showUserRegisterForm'])->name('register');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('login.submit');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('register.submit');

        Route::get('/forgot-password', [AuthController::class, 'showUserForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    // ---------------- Authenticated Routes ----------------
    Route::middleware(['authenticate.user:web', 'prevent.back.history:web'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('update');
        Route::post('/delete', [UserController::class, 'deleteAccount'])->name('delete');
        Route::post('/logout', [AuthController::class, 'userLogout'])->name('logout');

        // Courses
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [UserCourseController::class, 'index'])->name('index');
            Route::get('/my', [PurchaseController::class, 'index'])->name('my');
            Route::get('/explore/{courseId}', [UserCourseController::class, 'explore'])->name('explore');
            Route::get('/{course}/lessons/data', [UserLessonController::class, 'getLessons'])->name('lessons.data');
            Route::get('/lessons/{id}/stream', [UserLessonController::class, 'stream'])->name('lessons.stream');
            Route::get('/{courseId}/view', [UserLessonController::class, 'viewLessons'])->name('view');

            // Feedback
            Route::post('/feedback/store', [CourseFeedbackController::class, 'store'])->name('feedback.store');
            Route::get('/{courseId}/feedback', [CourseFeedbackController::class, 'index'])->name('feedback.list');
            Route::get('/{courseId}/feedback/check', [CourseFeedbackController::class, 'checkUserFeedback']);
            Route::get('/{courseId}/feedback/summary', [CourseFeedbackController::class, 'summary']);
        });

        // Quizzes
        Route::get('/quizzes', [UserQuizController::class, 'index'])->name('quizzes.index');
        Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('quizzes.show');
        Route::get('/quizzes/{quiz}/result', [UserQuizController::class, 'result'])->name('quizzes.result');
        Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('quizzes.submit');

        // Practice Tests
        Route::get('/lesson/{lessonId}/practice-test', [UserPracticeTestController::class, 'start'])->name('practice.start');
        Route::post('/lesson/{lessonId}/practice-test/start', [UserPracticeTestController::class, 'createAttempt'])->name('practice.start.attempt');
        Route::get('/practice-attempt/{attemptId}/questions', [UserPracticeTestController::class, 'showTest'])->name('practice.test');
        Route::get('/practice-attempt/{attemptId}/result', [UserPracticeTestController::class, 'result'])->name('practice.result');
        Route::post('/practice-attempt/{attemptId}/submit', [UserPracticeTestController::class, 'submitTest'])->name('practice.submit');

        // Payments
        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('/{courseId}', 'stripe')->name('payment.stripe');
            Route::post('/', 'stripePost')->name('payment.post');
        });
    });
});

// ======================================================================
// TRAINER ROUTES
// ======================================================================
Route::prefix('trainer')->name('trainer.')->group(function () {

    // ---------------- Guest ----------------
    Route::middleware(['guest:trainer', 'prevent.trainer.back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showTrainerLoginForm'])->name('login');
        Route::get('/register', [AuthController::class, 'showTrainerRegisterForm'])->name('register');
        Route::post('/login', [AuthController::class, 'trainerLogin'])->name('login.submit');
        Route::post('/register', [AuthController::class, 'trainerRegister'])->name('register.submit');

        Route::get('/forgot-password', [AuthController::class, 'showTrainerForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotTrainerPassword'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showTrainerResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetTrainerPassword'])->name('password.update');
    });

    // ---------------- Authenticated ----------------
    Route::middleware(['authenticate.user:trainer'])->group(function () {
        Route::get('/', [TrainerController::class, 'index'])->name('dashboard');
        Route::get('/profile', [TrainerController::class, 'profile'])->name('profile');
        Route::post('/update', [TrainerController::class, 'updateProfile'])->name('update');
        Route::post('/delete', [TrainerController::class, 'deleteAccount'])->name('delete');
        Route::post('/logout', [AuthController::class, 'trainerLogout'])->name('logout');

        Route::get('/report', [ReportController::class, 'index'])->name('report');
        Route::get('/students', [TrainerStudentController::class, 'index'])->name('students.index');
        Route::post('/{trainerId}/earnings/add/{courseId}', [TrainerController::class, 'addEarning'])->name('earnings.add');
        Route::get('/{trainerId}/earnings/total', [TrainerController::class, 'totalEarnings'])->name('earnings.total');

        // Courses
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [TrainerCourseController::class, 'index'])->name('index');
            Route::get('/create', [TrainerCourseController::class, 'create'])->name('create');
            Route::get('/my', [TrainerCourseController::class, 'myCourses'])->name('my');
            Route::get('/explore/{courseId}', [TrainerCourseController::class, 'explore'])->name('explore');
            Route::delete('/{course}', [TrainerCourseController::class, 'destroy'])->name('destroy');
            Route::get('/my-purchases', [PurchaseController::class, 'index'])->name('my.purchases');

            // Lessons
            Route::get('/{course}/lessons', [TrainerLessonController::class, 'manage'])->name('lessons.manage');
            Route::get('/{course}/lessons/create', [TrainerLessonController::class, 'create'])->name('lessons.create');
            Route::get('/{course}/lessons/view', [TrainerLessonController::class, 'viewLessons'])->name('lessons.view');
            Route::get('/{course}/lessons/data', [TrainerLessonController::class, 'getLessons'])->name('lessons.data');
            Route::get('/lessons/{id}/stream', [TrainerLessonController::class, 'stream'])->name('lessons.stream');
            Route::post('/{course}/lessons', [TrainerLessonController::class, 'store'])->name('lessons.store');
            Route::put('/lessons/update/{id}', [TrainerLessonController::class, 'update'])->name('lessons.update');
            Route::delete('/{course}/{lesson_Id}', [TrainerLessonController::class, 'destroy_lessson'])->name('lessons.destroy');

            // Course Feedback
            Route::get('/{courseId}/feedback', [CourseFeedbackController::class, 'index'])->name('feedback.list');
            Route::get('/{courseId}/feedback/summary', [CourseFeedbackController::class, 'summary']);
        });

        // Quizzes
        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('/', [QuizController::class, 'index'])->name('index');
            Route::get('/create', [QuizController::class, 'create'])->name('create');
            Route::post('/store', [QuizController::class, 'store'])->name('store');
            Route::post('/{quiz}/finalize', [QuizController::class, 'finalizeQuiz'])->name('finalize');
            Route::get('/{id}/questions', [QuizController::class, 'showQuestions'])->name('questions');
            Route::post('/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('questions.store');
            Route::delete('/questions/{question}', [QuizController::class, 'deleteQuestion'])->name('questions.delete');
        });

        // Practice Tests
        Route::resource('practice-tests', PracticeTestController::class);
        Route::post('practice-tests/{id}/import-questions', [PracticeTestController::class, 'importQuestions'])->name('practice-tests.import-questions');
        Route::get('practice-tests/{id}/import-questions', [PracticeTestController::class, 'showImportPage']);

        // Payments
        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('/{courseId}', 'stripe')->name('payment.stripe');
            Route::post('/', 'stripePost')->name('payment.post');
        });
    });
});

// ======================================================================
// ADMIN ROUTES
// ======================================================================
Route::prefix('admin')->middleware(['authenticate.user:web', 'admin.only', 'prevent.back.history:web'])->group(function () {
    Route::get('/', [AdminProfileController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.update');
    Route::post('/account/delete', [AdminProfileController::class, 'deleteAccount'])->name('admin.account.delete');

    // Users
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'showUserPage'])->name('index');
        Route::get('/fetch', [AdminProfileController::class, 'fetchAllUsers'])->name('fetch');
        Route::post('/add', [AdminProfileController::class, 'addUser'])->name('add');
        Route::post('/update/{id}', [AdminProfileController::class, 'updateUser'])->name('update');
        Route::delete('/delete/{id}', [AdminProfileController::class, 'deleteUser'])->name('delete');
    });

    // Trainers
    Route::prefix('trainers')->name('admin.trainers.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'showTrainerPage'])->name('index');
        Route::get('/fetch', [AdminProfileController::class, 'fetchAllTrainers'])->name('fetch');
        Route::post('/add', [AdminProfileController::class, 'addTrainer'])->name('add');
        Route::post('/update/{id}', [AdminProfileController::class, 'updateTrainer'])->name('update');
        Route::delete('/delete/{id}', [AdminProfileController::class, 'deleteTrainer'])->name('delete');
    });

    // Courses
    Route::prefix('courses')->name('admin.courses.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'showCoursePage'])->name('index');
        Route::get('/fetch', [AdminProfileController::class, 'fetchAllCourses'])->name('fetch');
        Route::post('/update-status/{id}', [AdminProfileController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{id}', [AdminProfileController::class, 'destroy'])->name('destroy');
    });

    // Optional
    Route::get('/quizzes', [AdminProfileController::class, 'fetchAllQuizzes'])->name('quizzes.index')->middleware('optional');
    Route::get('/reports', [AdminProfileController::class, 'reports'])->name('reports')->middleware('optional');
    Route::get('/settings', [AdminProfileController::class, 'settings'])->name('settings')->middleware('optional');

    // Logout
    Route::post('/logout', [AuthController::class, 'userLogout'])->name('admin.logout');
});

// ======================================================================
// CHATBOT & TELEGRAM
// ======================================================================
Route::post('/chatbot/send', [ChatBotController::class, 'send'])->middleware(['auth.any'])->name('chatbot.send');
Route::post('/send-to-telegram', [TelegramController::class, 'sendMessage'])->middleware(['auth.any'])->name('send.to.telegram');

// ======================================================================
// NOTIFICATIONS
// ======================================================================
Route::prefix('notifications')->group(function () {
    Route::post('/create', [NotificationController::class, 'store'])->name('notifications.create');
    Route::get('/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// ======================================================================
// CHAT
// ======================================================================
Route::prefix('chat')->middleware(['auth.any'])->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/room/{id}', [ChatController::class, 'room'])->name('chat.room');
    Route::post('/request/{id}/{type}', [ChatController::class, 'sendRequest'])->name('chat.request');
    Route::post('/cancel/{id}', [ChatController::class, 'cancelRequest'])->name('chat.cancel');
    Route::delete('/unfriend', [ChatController::class, 'unfriend'])->name('unfriend');
    Route::get('/requests', [ChatRequestController::class, 'myRequests'])->name('chat.requests');
    Route::post('/accept/{id}', [ChatRequestController::class, 'acceptRequest'])->name('chat.accept');
    Route::post('/decline/{id}', [ChatRequestController::class, 'declineRequest'])->name('chat.decline');
});



// ======================================================================
// MISC PAGES
// ======================================================================
Route::view('/video-call', 'chat.videoCall');
Route::view('/landing-page', 'landingPage');
Route::view('/contact', 'contact');
