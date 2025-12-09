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
Public Routes
*/

Route::get('/', [WTSController::class, 'index'])->name('home');
Route::get('/home', [WTSController::class, 'show'])->name('homeIn');

// AUTH
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// FORGOT PASSWORD
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmail'])->name('forgot.email');
Route::post('/forgot-password', [ForgotPasswordController::class, 'checkEmail'])->name('forgot.send');

Route::get('/reset-password/{email}', [ForgotPasswordController::class, 'showReset'])->name('reset.page');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.save');

// PRODUCT PAGES
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');


/*
Seller Routes
*/
Route::prefix('seller')->group(function () {
    // PROFILE
    Route::get('/profile/{id}', [SellerController::class, 'index'])->name('seller.profile');

    // PRODUCTS
    Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
    Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('seller.products.destroy');

    // SETTINGS
    Route::get('/settings', [SellerController::class, 'showSettings'])->name('seller.settings');
    //Route::get('/settings/{tab}', [SellerController::class, 'showSettings'])->name('seller.settings.tab');
    Route::post('/settings/store-info', [SellerController::class, 'updateStoreInfo'])->name('seller.settings.update.store');
});


/*
Auth-Protected Routes
*/
Route::middleware('auth')->group(function () {

    /*
    Cart
    */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update-qty', [CartController::class, 'updateQty'])->name('cart.updateQty');
    Route::post('/cart/delete-item', [CartController::class, 'deleteItem'])->name('cart.deleteItem');
    Route::post('/cart/add-ajax', [CartController::class, 'addAjax'])->name('cart.add-ajax');

    /*
    Order
    */
    Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/order/{order}/complete', [OrderController::class, 'completeOrder'])->name('order.complete');
    Route::delete('/order/{order}/delete', [OrderController::class, 'deleteOrder'])->name('order.delete');

    /*
    Payment
    */
    Route::get('/payment/{order_id}', [PaymentController::class, 'page'])
    ->name('payment.page');
    Route::post('/payment/{order_id}', [PaymentController::class, 'pay'])
    ->name('payment.pay');

    /*
    Address Change
    */
    // PAGE ganti alamat (GET)
    Route::get('/address/change/{order_id}', 
        [BuyerController::class, 'changeAddressPage']
    )->name('address.change.page');

    // SAVE alamat baru (POST)
    Route::post('/address/change', 
        [BuyerController::class, 'saveAddress']
    )->name('address.change.save');

    /*
    Wishlist
    */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/add-ajax', [WishlistController::class, 'addAjax'])->name('wishlist.add-ajax');

    /*
    Buyer Profile
    */
    Route::get('/buyer/settings', [BuyerController::class, 'settings'])->name('buyer.settings');
    Route::post('/buyer/settings/personal-info', [BuyerController::class, 'updatePersonalInfo'])
        ->name('buyer.settings.update.personal');
    Route::get('/seller/create', [SellerController::class, 'showCreateStore'])->name('seller.create.form');
    Route::post('/seller/register', [SellerController::class, 'registerStore'])->name('seller.register');
    /*
    Chat
    */
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/with/{receiverId}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chat}/send', [ChatController::class, 'store'])->name('store');
    });

    Route::get('/seller/products/search', [SellerProductController::class, 'search'])->name('seller.products.search');

});
