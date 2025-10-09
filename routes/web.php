<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/all', [AdminController::class, 'fetchAllUsers']);
Route::get('/trainer/all', [AdminController::class, 'fetchAllTrainers']);
