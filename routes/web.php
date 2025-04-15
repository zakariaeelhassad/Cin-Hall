<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function(){
    return view('register');
})->name('register')->middleware('guest');

Route::get('/login', function(){
    return view('login');
})->name('login')->middleware('guest');

Route::get('/films', function(){
    return view('films');
})->name('films');

Route::get('/sience', function(){
    return view('sience');
})->name('sience');

Route::get('/salle', function(){
    return view('salle');
})->name('salle');



Route::get('/dashboard', function(){
    return view('dashboard');
})->name('dashboard');