<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('/map', [MapController::class, 'index'])->name('map');

Route::get('/profile/{id}', [ProfileController::class, 'index'])->middleware('web');

Route::get('/comments/{art_id}', [CommentController::class, 'index'])->name('comments.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    // Общие маршруты
    Route::post('/check-point', [MapController::class, 'checkPoint'])->name('check-point');
    Route::get('/arts/create', [ArtController::class, 'create'])->name('arts.create');
    Route::post('/arts/store', [ArtController::class, 'store'])->name('arts.store');

    Route::delete('/arts/{art}', [ArtController::class, 'destroy'])->name('arts.delete');

    Route::patch('/arts/{art}/update-field', [ArtController::class, 'updateField'])->name('arts.update-field');

    Route::get('/moderation', [ModeratorController::class, 'ModerationPanel'])->name('moderation');

    Route::get('/arts/{art}/moderate', [ModeratorController::class, 'artModerate'])->name('arts.moderate');
    Route::put('/arts/{art}/approve', [ModeratorController::class, 'artApprove'])->name('arts.approve');
    Route::put('/arts/{art}/reject', [ModeratorController::class, 'artReject'])->name('arts.reject');
    
    Route::get('/arts/{art}/report', [ModeratorController::class, 'artReportCreate'])->name('reports.create');
    Route::post('/arts/{art}/report', [ModeratorController::class, 'artReportStore'])->name('reports.store');
    
    Route::delete('/reports/{report}/delete-art', [ModeratorController::class, 'resolveByDeletingArt'])->name('reports.resolve.delete-art');
    Route::patch('/reports/{report}/reject', [ModeratorController::class, 'resolveByRejectingReport'])->name('reports.resolve.reject');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{id}/restore', [CommentController::class, 'restore'])->name('comments.restore');
});

Route::get('/arts/{art}', [ArtController::class, 'show'])->name('art.show');
