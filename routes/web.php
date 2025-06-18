<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/bookings/{slug}', [BookingController::class, 'show'])->name('booking');
Route::post('/payment/verify', [BookingController::class, 'verifyPayment'])->name('razorpay.verify');
Route::get('/download/{slug}', [BookingController::class, 'download'])->name('download');
Route::get('/thank-you/{slug}', [BookingController::class, 'thankYou'])->name('thank-you');
Route::get('/failed/{slug}', [BookingController::class, 'failed'])->name('failed');
Route::get('/page-expired', function () {
    return view('bookings.expired');
})->name('page-expired');