<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WTSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuyerController;

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

Route::get('/seller/products', [SellerProductController::class, 'index'])->name('seller.products');
Route::get('/seller/products/{id}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
Route::get('/seller/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
Route::get('/seller/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
Route::post('/seller/products', [SellerProductController::class, 'store'])->name('seller.products.store');
Route::get('/seller/products/{id}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
Route::put('/seller/products/{id}', [SellerProductController::class, 'update'])->name('seller.products.update');
Route::prefix('chat')->group(function () {
    Route::get('/seller', [ChatController::class, 'sellerChat'])
        ->name('chat.seller');
    Route::get('/buyer', [ChatController::class, 'buyerChat'])
        ->name('chat.buyer');
});

Route::get('/buyer/settings', [BuyerController::class, 'settings'])->name('buyerSettings');
Route::get('/buyer/favorites', [BuyerController::class, 'favorites'])->name('buyerFavorites');
Route::get('/buyer/chat', [BuyerController::class, 'chat'])->name('buyerChat');
Route::get('/buyer/keranjang', [BuyerController::class, 'cart'])->name('buyerKeranjang');
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('detailProduct');