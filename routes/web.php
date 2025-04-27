<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/bookings/{slug}', [BookingController::class, 'show']);
Route::post('/bookings/{slug}/pay', [BookingController::class, 'pay']);
Route::get('/thank-you/{slug}', [BookingController::class, 'thankYou']);
