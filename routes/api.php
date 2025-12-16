<?php

use App\Http\Controllers\Admin\AdminMovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;


// 🚀 HEALTH CHECK
Route::get('/', function () {
    return response()->json(['status' => 'API is running 🚀']);
});


/* ---------------------- USER AUTH (bookmyshow_ui) ---------------------- */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn() => auth()->Auth::user()());
    Route::post('/logout', [AuthController::class, 'logout']);
});


/* ---------------------- ADMIN AUTH (adminpanel_ui) ---------------------- */
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/signup', [AdminLoginController::class, 'signup']);
    Route::put('/movies/{id}', [AdminMovieController::class, 'update']);
    Route::get('/movies/{id}', [AdminMovieController::class, 'show']);

});

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/movies', AdminMovieController::class);
});


/* ---------------------- BOOKINGS (User) ---------------------- */
Route::prefix('bookings')->group(function () {
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/success', [BookingController::class, 'paymentSuccess']);
    Route::get('/cancel', [BookingController::class, 'paymentCancel']);
});


/* ---------------------- PAYMENTS ---------------------- */
Route::prefix('payments')->group(function () {
    Route::get('/', fn() => Payment::all());
    Route::post('/verify', [\App\Http\Controllers\PaymentController::class, 'store']);
});

