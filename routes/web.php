<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\WTSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [WTSController::class, 'index'])->name('home');
Route::get('/home', [WTSController::class, 'show'])->name('homeIn');

// Auth pages
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmail'])->name('forgot.email');
Route::post('/forgot-password', [ForgotPasswordController::class, 'checkEmail'])->name('forgot.send');

Route::get('/reset-password/{email}', [ForgotPasswordController::class, 'showReset'])->name('reset.page');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.save');

// Product detail + search
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/api/search', [ProductController::class, 'searchAjax'])->name('products.search.ajax');


/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->group(function () {

    // CRUD Produk Seller
    Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
    Route::get('/products/{id}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/products/{id}', [SellerProductController::class, 'update'])->name('seller.products.update');

    // Seller Settings
    Route::get('/settings', [SellerController::class, 'showSettings'])->name('seller.settings');
    Route::get('/settings/{tab}', [SellerController::class, 'showSettings'])->name('seller.settings.tab');
    Route::post('/settings/store-info', [SellerController::class, 'updateStoreInfo'])->name('seller.settings.update.store');

    // Placeholder
    Route::get('/settings/orders', fn() => view('seller.settings', ['activeTab' => 'orders']))->name('seller.settings.orders');
    Route::get('/settings/user-page', fn() => view('seller.settings', ['activeTab' => 'user-page']))->name('seller.settings.user-page');
});


/*
|--------------------------------------------------------------------------
| Buyer Routes
|--------------------------------------------------------------------------
*/
Route::get('/buyer/settings', [BuyerController::class, 'settings'])->name('buyer.settings'); 
Route::get('/buyer/favorites', [BuyerController::class, 'favorites'])->name('buyer.favorites');
Route::get('/buyer/keranjang', [BuyerController::class, 'cart'])->name('buyer.cart');


/*
|--------------------------------------------------------------------------
| Auth-Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    // Orders
    Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Payment
    Route::post('/payment/{order_id}', [PaymentController::class, 'pay'])->name('payment.pay');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Buyer update profile
    Route::post('/buyer/settings/personal-info', [BuyerController::class, 'updatePersonalInfo'])
        ->name('buyer.settings.update.personal');

    // Buyer → Seller
    Route::get('/seller/create', [SellerController::class, 'showCreateStore'])->name('seller.create.form');
    Route::post('/seller/create', [SellerController::class, 'registerStore'])->name('seller.register');


    /*
    |--------------------------------------------------------------------------
    | Chat Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/with/{receiverId}', [ChatController::class, 'show'])->name('show.user');
        Route::post('/{chat}/{chat}', [ChatController::class, 'show'])->name('show'); // (ini harusnya diperbaiki, nanti jelasin ya)
        Route::post('/{chat}/send', [ChatController::class, 'store'])->name('store');
    });

});
