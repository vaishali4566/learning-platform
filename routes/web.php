<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/all', [AdminController::class, 'fetchAllUsers']);
Route::get('/trainer/all', [AdminController::class, 'fetchAllTrainers']);

//payment route
Route::get('/payment' , [PaymentController :: class , "base"]);
Route::controller(PaymentController::class)->group(function () {
    Route::get('/stripe', 'stripe');
    Route::post('/stripe', 'stripePost')->name('stripe.post');
});
