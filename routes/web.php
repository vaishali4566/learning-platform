<?php

use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTrainerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TrainerController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Trainer\QuizController;
use App\Http\Controllers\Web\UserQuizController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Trainer\TrainerCourseController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use App\Http\Controllers\Trainer\TrainerStudentController;
use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\User\UserDashboardController;

// --------------------------------------------------
// Root redirect
// --------------------------------------------------
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


/*
|--------------------------------------------------------------------------
| USER AUTH ROUTES
|--------------------------------------------------------------------------
*/
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

        // User quizzes
        Route::get('/quizzes', [UserQuizController::class, 'index'])->name('user.quizzes.index');
        Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
        Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
    });
});

/*
|--------------------------------------------------------------------------
| TRAINER AUTH ROUTES
|--------------------------------------------------------------------------
*/
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
        // Route::get('/', [TrainerController::class, 'index'])->name('trainer.dashboard');
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

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['admin.only'])->group(function () {
    Route::get('/', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'fetchAllUsers'])->name('admin.users');
    Route::get('/trainers', [AdminController::class, 'fetchAllTrainers'])->name('admin.trainers');
});


/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::get('/{courseId}', 'stripe')->name('payment.stripe');
    Route::post('/', 'stripePost')->name('payment.post');
});


//////////////////////////
// FORGOT & RESET PASSWORD ROUTES
//////////////////////////

// Show forgot password form


// Submit email to send reset link
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Show reset password form (the link in email will use this route)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset'); // important: this name must be "password.reset"

// Submit new password
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::prefix('user')->middleware(['authenticate.user:web'])->group(function () {
    Route::get('/quizzes', [UserQuizController::class, 'index'])->name('user.quizzes.index');
    Route::get('/quizzes/{quiz}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
});

/*
|--------------------------------------------------------------------------
| COURSES ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/chatbot/send', [ChatBotController::class, 'send'])->name('chatbot.send');

Route::group(['prefix' => 'courses'], function () {

    Route::post('/', [CoursesController::class, 'store']);     //submit courses data    //trainer only
    Route::get('/create', [CoursesController::class, 'create'])->name('courses.create');  //show create form    //trainer only
    Route::delete('/{id}', [CoursesController::class, 'delete']);       //delete course //trainer only
    Route::get('/trainer', [CoursesController::class, 'showTrainerCourses'])->name('courses.trainercourses');        //show trainer courses page
    Route::put('/trainer/{id}', [CoursesController::class, 'update'])->name('courses.update');   //update trainer course
    Route::get('/trainer/course/count', [CoursesController::class, 'coursesWithPurchaseCount'])->name('course.purchase');  //each course purchase count

    Route::get('/data', [CoursesController::class, 'getAll']);     //get all courses on click
    Route::get('/', [CoursesController::class, 'index'])->name('courses.index');    //show all courses page

    Route::get('/{id}/lessons', [LessonsController::class, 'lessonsByCourse']);     //get all lesson by courses
    Route::get('/my', [CoursesController::class, 'myCourses'])->name('courses.mycourses');       //show my course page

    Route::get('/{courseId}/explore', [CoursesController::class, 'explore'])->name('courses.explore');       //show explore page
    Route::get('/{id}', [CoursesController::class, 'getCourse']);       //show trainer course on click
});

// Route::group(['prefix' => 'courses'], function () {

//     // 🔒 Trainer-only routes
//     Route::middleware('auth:trainer')->group(function () {
//         Route::post('/', [CoursesController::class, 'store']); // trainer only
//         Route::get('/create', [CoursesController::class, 'create'])->name('courses.create'); // trainer only
//         Route::delete('/{id}', [CoursesController::class, 'delete']); // trainer only
//         Route::get('/trainer', [CoursesController::class, 'showTrainerCourses'])->name('courses.trainercourses'); // trainer only
//         Route::put('/trainer/{id}', [CoursesController::class, 'update'])->name('courses.update'); // trainer only
//         Route::get('/trainer/course/count', [CoursesController::class, 'coursesWithPurchaseCount'])->name('course.purchase'); // trainer only
//     });

//     // 🔐 Authenticated users (user or trainer)
//     Route::middleware('auth:web,trainer')->group(function () {
//         Route::get('/data', [CoursesController::class, 'getAll']); // get all courses
//         Route::get('/', [CoursesController::class, 'index'])->name('courses.index'); // show all courses
//         Route::get('/{id}/lessons', [LessonsController::class, 'lessonsByCourse']); // get all lessons by course
//         Route::get('/my', [CoursesController::class, 'myCourses'])->name('courses.mycourses'); // show my courses
//         Route::get('/{courseId}/explore', [CoursesController::class, 'explore'])->name('courses.explore'); // explore course
//         Route::get('/{id}', [CoursesController::class, 'getCourse']); // show single course
//     });
// });

/*
|--------------------------------------------------------------------------
| LESSONS ROUTES
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'lessons'], function () {
    Route::get('/lessonform', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.create');
    Route::get('/view/{id}', [LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('all/{id}', [LessonsController::class, 'viewLesson1'])->name('lessons.alllesson');
    Route::get('/{id}', [LessonsController::class, 'stream']);
});

// Route::get('/admin/dashboard', function () {
//     return view('admin.anudashboard');
// })->name('admin.dashboard');

// Route::get('/trainer/dashboard', function () {
//     return view('trainer.anudashboard');
// })->name('trainer.dashboard1');

// Route::get('/user/dashboard', function () {
//     return view('user.anudashboard');
// })->name('user.dashboard');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin.only'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Nested group for admin courses
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [AdminCourseController::class, 'index'])->name('index');
            Route::get('/create', [AdminCourseController::class, 'create'])->name('create');
            Route::post('/', [AdminCourseController::class, 'store'])->name('store');
        });

        // Trainers management
        Route::resource('trainers', AdminTrainerController::class);
});

Route::prefix('trainer')
    ->name('trainer.')
    ->middleware(['trainer.only'])
    ->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');

        // Trainer’s own courses
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [TrainerCourseController::class, 'index'])->name('index');
            Route::get('/create', [TrainerCourseController::class, 'create'])->name('create');
            Route::post('/', [TrainerCourseController::class, 'store'])->name('store');
            Route::get('/my', [TrainerCourseController::class, 'myCourses'])->name('my');
        });

        Route::get('/students', [TrainerStudentController::class, 'index'])->name('students');
    });

Route::prefix('user')
    ->name('user.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // User-specific course routes
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [UserCourseController::class, 'index'])->name('index');
            Route::get('/{course}', [UserCourseController::class, 'show'])->name('show');
            Route::post('/{course}/enroll', [UserCourseController::class, 'enroll'])->name('enroll');
        });
    });
