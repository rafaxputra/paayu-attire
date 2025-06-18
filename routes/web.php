<?php

use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Public Routes
 * |--------------------------------------------------------------------------
 * |
 * | These routes are accessible to everyone.
 * |
 */
Route::name('front.')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('index');
    Route::get('/details/{product:slug}', [FrontController::class, 'details'])->name('details');
    Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
});

/*
 * |--------------------------------------------------------------------------
 * | Guest-Only Authentication Routes
 * |--------------------------------------------------------------------------
 * |
 * | These routes are for users who are not logged in.
 * |
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('front.auth.google');
    
});
// Google OAuth Callback - Accessible to both guests and authenticated users
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

/*
 * |--------------------------------------------------------------------------
 * | Authenticated Routes
 * |--------------------------------------------------------------------------
 * |
 * | These routes require the user to be logged in.
 * |
 */
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Customer Dashboard & Profile
    Route::name('front.customer.')->prefix('customer')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'customerDashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'editProfile'])->name('editProfile');
        Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('updateProfile');
        Route::delete('/delete-account', [CustomerController::class, 'deleteAccount'])->name('deleteAccount');
        Route::post('/unlink-google', [CustomerController::class, 'unlinkGoogle'])->name('unlinkGoogle');
        Route::get('/set-password', [CustomerController::class, 'showSetPasswordForm'])->name('setPassword');
        Route::post('/set-password', [CustomerController::class, 'setPassword'])->name('setPassword.save');
    });

    // Transactions and Orders
    Route::name('front.')->group(function () {
        Route::get('/transactions', [FrontController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/details', [FrontController::class, 'transactions_details'])->name('transactions.details');
        Route::get('/success-booking/{transaction}', [FrontController::class, 'success_booking'])->name('success.booking');

        // Rental Flow
        Route::get('/booking/{product:slug}', [FrontController::class, 'booking'])->name('booking');
        Route::post('/booking/{product:slug}/save', [FrontController::class, 'booking_save'])->name('booking_save');
        Route::get('/checkout/{product:slug}/payment', [FrontController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/finish', [FrontController::class, 'checkout_store'])->name('checkout.store');
        Route::put('/rental/details/{rentalTransaction}/cancel', [FrontController::class, 'cancelRentalOrder'])->name('rental.cancel');

        // Custom Order Flow
        Route::get('/custom', [FrontController::class, 'custom'])->name('custom');
        Route::post('/custom/order', [FrontController::class, 'storeCustomOrder'])->name('custom.order.store');
        Route::get('/custom/details/{customTransaction}', [FrontController::class, 'customTransactionDetails'])->name('custom.details');
        Route::get('/custom/success/{customTransaction}', [FrontController::class, 'customSuccess'])->name('custom.success');
        Route::post('/custom/details/{customTransaction}/upload-payment-proof', [FrontController::class, 'uploadCustomPaymentProof'])->name('custom.uploadPaymentProof');
        Route::put('/custom/details/{customTransaction}/cancel', [FrontController::class, 'cancelCustomOrder'])->name('custom.cancel');
        Route::post('/custom/approve/{customTransaction}', [FrontController::class, 'approveCustomOrder'])->name('custom.approve');

        // Commenting
        Route::post('/contact/comment', [FrontController::class, 'storeComment'])->name('contact.comment');
    });

    // Route khusus untuk link Google ke akun yang sudah login
    Route::get('/auth/google/link', [AuthController::class, 'redirectToGoogleLink'])->name('front.auth.google.link');
});
