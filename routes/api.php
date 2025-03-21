<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SalleContoller;
use App\Http\Controllers\SiegeController;
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

Route::get("/users" , [AuthController::class , "index"]);
Route::post("/register" , [AuthController::class , "store"]);

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post("/film" , [FilmController::class , "store"]);
});

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post("/salle" , [SalleContoller::class , "store"]);
});


Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post('/salles/{salle_id}/sieges', [SiegeController::class, 'store']);
});
