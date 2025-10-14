<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user', [UserController :: class , 'sayHi'] );
Route::get('/user/all', [UserController :: class , 'getAllUser'] );

Route::group(['prefix'=>'courses'], function(){
    Route::get('/', [CoursesController::class, 'showCreateForm'])->name('courses.create');
    Route::post('/', [CoursesController::class, 'create'])->name('courses.store');
});

Route::group(['prefix' => 'lessons'], function () {
    Route::get('/', [LessonsController::class, 'showLessonForm'])->name('lessons.create');
    Route::post('/', [LessonsController::class, 'create'])->name('lessons.store');
});
