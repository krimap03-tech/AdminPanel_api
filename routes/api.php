<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminMovieController;
use App\Http\Controllers\Api\AuthUiController;
use App\Http\Controllers\Api\MovieStatsController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PospayController;
use App\Http\Middleware\AdminAuth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| HEALTH CHECK
|--------------------------------------------------------------------------
*/
Route::get('/pospay',[PospayController::class,'show']);
Route::get('/pos',[PosController::class,'show']);
Route::get('/', fn () => response()->json([
    'status' => 'API is running 🚀'
]));

/*
|--------------------------------------------------------------------------
| USER AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::Post('/signup',[AuthUiController::class,'signup']);
    // Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/signup', [AuthUiController::class, 'signup']);
});

/*
|--------------------------------------------------------------------------
| USER MOVIES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::get('/{id}', [MovieController::class, 'show']);
    Route::get('/{movieId}/stats', [MovieStatsController::class, 'movieStats']);
});

/*
|--------------------------------------------------------------------------
| BOOKINGS (PUBLIC – used for payment gateway callbacks)
|--------------------------------------------------------------------------
*/
Route::prefix('bookings')->group(function () {
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/success', [BookingController::class, 'paymentSuccess']);
    Route::get('/cancel', [BookingController::class, 'paymentCancel']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Logged-in user
    Route::get('/user', fn () => auth()->user());

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Payments (PROTECTED)
    Route::post('/payments', [PaymentController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/signup', [AdminLoginController::class, 'signup']);
});

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    // ->middleware(['auth:sanctum', AdminAuth::class])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ADMIN USERS
        |--------------------------------------------------------------------------
        */
        Route::get('/users', function () {
            return response()->json([
                'status' => true,
                'data' => User::select(
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at'
                )->get()
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | ADMIN MOVIES
        |--------------------------------------------------------------------------
        */
        Route::prefix('movies')->group(function () {
            Route::get('/', [AdminMovieController::class, 'index']);
            Route::post('/', [AdminMovieController::class, 'store']);
            Route::get('/{id}', [AdminMovieController::class, 'show']);
            Route::put('/{id}', [AdminMovieController::class, 'update']);
            Route::delete('/{id}', [AdminMovieController::class, 'destroy']);
        });
    });

/*
|--------------------------------------------------------------------------
| TICKETS (PUBLIC – for QR / ticket view)
|--------------------------------------------------------------------------
*/
Route::get('/ticket/{id}', function ($id) {
    return \App\Models\Ticket::with('booking.movie')->findOrFail($id);
});
