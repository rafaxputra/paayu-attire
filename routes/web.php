<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Front\AuthController; // Import the new AuthController
use Illuminate\Support\Facades\Route;

Route::name('front.')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('index');

    Route::get('/transactions', [FrontController::class, 'transactions'])->name('transactions');
    Route::post('/transactions/details', [FrontController::class, 'transactions_details'])->name('transactions.details');

    Route::get('/details/{product:slug}', [FrontController::class, 'details'])->name('details');

    // Booking and Custom routes will be protected later
    Route::get('/booking/{product:slug}', [FrontController::class, 'booking'])->name('booking');
    Route::post('/booking/{product:slug}/save', [FrontController::class, 'booking_save'])->name('booking_save');

    Route::get('/success-booking/{transaction}', [FrontController::class, 'success_booking'])->name('success.booking');

    Route::post('/checkout/finish', [FrontController::class, 'checkout_store'])->name('checkout.store');

    Route::get('/checkout/{product:slug}/payment', [FrontController::class, 'checkout'])->name('checkout');

    // Custom Kebaya Order Routes
    Route::get('/custom', [FrontController::class, 'custom'])->name('custom');
    Route::post('/custom/order', [FrontController::class, 'storeCustomOrder'])->name('custom.order.store');
    Route::get('/custom/details/{customTransaction}', [FrontController::class, 'customTransactionDetails'])->name('custom.details');
    Route::post('/custom/details/{customTransaction}/upload-payment-proof', [FrontController::class, 'uploadCustomPaymentProof'])->name('custom.uploadPaymentProof');
    Route::put('/custom/details/{customTransaction}/cancel', [FrontController::class, 'cancelCustomOrder'])->name('custom.cancel');
    Route::post('/custom/approve/{customTransaction}', [FrontController::class, 'approveCustomOrder'])->name('custom.approve');
    Route::get('/custom/success/{customTransaction}', [FrontController::class, 'customSuccess'])->name('custom.success');

    // Contact Page Route
    Route::get('/contact', [FrontController::class, 'contact'])->name('contact');

    // Removed old routes
    // Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('category');
    // Route('/brand/{brand:slug}/products', [FrontController::class, 'brand'])->name('brand');
    // Route::get('/booking/check', [FrontController::class, 'my_booking'])->name('my-booking');
});

// Authentication Routes using the new AuthController
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Added logout route

// Socialite Routes using the new AuthController
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('front.auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Comment User
Route::get('/contact', [FrontController::class, 'getComments'])->name('front.contact');
Route::post('/contact/comment', [FrontController::class, 'storeComment'])->middleware('auth')->name('front.contact.comment');

// Authenticated Customer Routes
Route::middleware(['auth'])->name('front.customer.')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'customerDashboard'])->name('dashboard');
    Route::get('/customer/profile', [CustomerController::class, 'editProfile'])->name('editProfile');
    Route::put('/customer/profile', [CustomerController::class, 'updateProfile'])->name('updateProfile');
    Route::delete('/customer/delete-account', [CustomerController::class, 'deleteAccount'])->name('deleteAccount');
});

// Routes that require authentication but are part of the main front group
Route::middleware(['auth'])->name('front.')->group(function () {
    Route::get('/booking/{product:slug}', [FrontController::class, 'booking'])->name('booking');
    Route::post('/booking/{product:slug}/save', [FrontController::class, 'booking_save'])->name('booking_save');
    Route::get('/checkout/{product:slug}/payment', [FrontController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/finish', [FrontController::class, 'checkout_store'])->name('checkout.store');
    Route::get('/custom', [FrontController::class, 'custom'])->name('custom');
    Route::post('/custom/order', [FrontController::class, 'storeCustomOrder'])->name('custom.order.store');
    Route::post('/custom/details/{customTransaction}/upload-payment-proof', [FrontController::class, 'uploadCustomPaymentProof'])->name('custom.uploadPaymentProof');
    Route::put('/custom/details/{customTransaction}/cancel', [FrontController::class, 'cancelCustomOrder'])->name('custom.cancel');
    Route::post('/custom/approve/{customTransaction}', [FrontController::class, 'approveCustomOrder'])->name('custom.approve');
});
