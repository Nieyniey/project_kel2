<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WTSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;

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

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/payment', [PaymentController::class, 'payment'])->name('payment');
Route::get('/change-address', [PaymentController::class, 'changeAddress'])->name('change.address');

Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::get('/track-order', [\App\Http\Controllers\OrderController::class, 'track'])->name('track.order');
