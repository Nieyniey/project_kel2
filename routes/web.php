<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WTSController;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('HomePageMain');
// });

Route::get('/', [WTSController::class, 'index']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/logout', [AuthController::class, 'showLogout'])->name('logout');
