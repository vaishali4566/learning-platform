<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/register', [AuthController :: class , 'userRegistration'] );
Route::post('/user/login', [AuthController :: class , 'userLogin'] );
Route::post('/trainer/register', [AuthController :: class , 'trainerRegister'] );
Route::post('/trainer/login', [AuthController :: class , 'trainerLogin'] );
Route::middleware('auth:sanctum')->post('/user/logout', [AuthController::class, 'logout']);