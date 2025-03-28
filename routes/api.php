<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\SiegeController;
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


Route::apiResource('users', \App\Http\Controllers\UserController::class);
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::apiResource('filme', \App\Http\Controllers\FilmController::class);

    Route::apiResource('salle', \App\Http\Controllers\SalleController::class);

    Route::apiResource('siege', \App\Http\Controllers\SiegeController::class);
        Route::post('/siege/salle/{salle_id}', [SiegeController::class, 'store']);

    Route::apiResource('seance', \App\Http\Controllers\SeanceController::class);
        Route::post('/seance/salle/{salle_id}/filme/{film_id}', [SeanceController::class, 'store']);

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



