<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WTSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;

// Route::get('/', function () {
//     return view('HomePageMain');
// });

Route::get('/', [WTSController::class, 'index']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/logout', [AuthController::class, 'showLogout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('forgot.email');
Route::post('/forgot-password/send', [ForgotPasswordController::class, 'sendCode'])->name('forgot.send');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('forgot.verify');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyCode'])->name('forgot.verify.submit');
Route::get('/forgot-password/new', [ForgotPasswordController::class, 'showNewPassword'])->name('forgot.new');
Route::post('/forgot-password/new', [ForgotPasswordController::class, 'updatePassword'])->name('forgot.update');
