<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('film', \App\Http\Controllers\FilmController::class);
    Route::apiResource('salle', \App\Http\Controllers\SalleController::class);
    Route::apiResource('siege', \App\Http\Controllers\SiegeController::class);
});

// Route::get('users', [UserController::class, 'index']);
