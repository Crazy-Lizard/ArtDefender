<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('/map', [MapController::class, 'index'])->name('map');

// Route::get('/login', [UserController::class, 'login'])->name('login');
// Route::post('/login/confirm', [UserController::class, 'login_confirm']);

// Route::get('/register', [UserController::class, 'register'])->name('register');
// Route::post('/register/confirm', [UserController::class, 'register_confirm']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
