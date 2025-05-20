<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('/map', [MapController::class, 'index'])->name('map');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    // Общие маршруты
    Route::get('/profile/{id}', [ProfileController::class, 'index']);

    Route::post('/check-point', [MapController::class, 'checkPoint'])->name('check-point');
    Route::get('/arts/create', [ArtController::class, 'create'])->name('arts.create');
    Route::post('/arts/store', [ArtController::class, 'store'])->name('arts.store');

    Route::get('/arts/{art}', [ArtController::class, 'show'])->name('art.show');

    Route::get('/requests', [ArtController::class, 'ShowRequests'])->name('requests');
    Route::get('/arts/{art}/moderate', [ArtController::class, 'artModerate'])->name('arts.moderate');
    Route::put('/arts/{art}/approve', [ArtController::class, 'artApprove'])->name('arts.approve');
    Route::put('/arts/{art}/reject', [ArtController::class, 'artReject'])->name('arts.reject');

    Route::delete('/arts/{art}', [ArtController::class, 'destroy'])->name('arts.delete');
});
