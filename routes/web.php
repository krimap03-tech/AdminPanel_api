<?php

use App\Http\Controllers\MovieController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('movies', MovieController::class)->withoutMiddleware(['auth:sanctum', AdminAuth::class]);