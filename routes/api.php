<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\SiegeController;
use App\Http\Controllers\UserController;
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



// Route::apiResource('users', \App\Http\Controllers\UserController::class);
// Route::middleware('api')->group(function () {
//     Route::post('/users', [UserController::class, 'store']);
// });

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    //Route::post('refresh', [AuthController::class, 'refresh']);
    //Route::get('me', [AuthController::class, 'me']);
});

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
        Route::apiResource('filme', \App\Http\Controllers\FilmController::class);

    Route::apiResource('salle', \App\Http\Controllers\SalleController::class);

    Route::apiResource('siege', \App\Http\Controllers\SiegeController::class);
    Route::apiResource('seance', \App\Http\Controllers\SeanceController::class);

    // Route::apiResource('reservation', \App\Http\Controllers\ReservationController::class);
    //     Route::post('/reservation/seance/{seance_id}', [ReservationController::class, 'store']);

});

Route::middleware('auth:api')->group(function () {
    Route::resource('reservations', ReservationController::class);

    Route::post('/reservations', [ReservationController::class, 'store']); 
    Route::put('/reservations/{id}', [ReservationController::class, 'update']); 
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm']); 
    Route::delete('/reservations/{id}', [ReservationController::class, 'cancel']); 
    Route::post('/payment/create-checkout-session', [PaymentController::class, 'createCheckoutSession']); 
    Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook']);

    Route::get('/payment/success/{reservation_id}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

});



