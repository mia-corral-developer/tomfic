<?php

declare(strict_types=1);

use App\Http\Controllers\Countings\CountingController;
use App\Http\Controllers\Countings\RoundCaptureController;
use Illuminate\Support\Facades\Route;

/*
 * Countings web routes (tomfic-field). Loaded inside the `auth` group in
 * routes/web.php, so every route here is already authenticated.
 */

Route::get('/countings', [CountingController::class, 'index'])->name('countings.index')->middleware('permission:view_countings');
Route::get('/countings/create', [CountingController::class, 'create'])->name('countings.create')->middleware('permission:create_countings');
Route::post('/countings', [CountingController::class, 'store'])->name('countings.store')->middleware('permission:create_countings');
Route::get('/countings/{counting}', [CountingController::class, 'show'])->name('countings.show')->middleware('permission:view_countings|capture_countings');
Route::post('/countings/{counting}/rounds', [CountingController::class, 'storeRound'])->name('countings.rounds.store')->middleware('permission:manage_countings');
Route::post('/countings/rounds/{round}/open', [CountingController::class, 'openRound'])->name('countings.rounds.open')->middleware('permission:manage_countings');
Route::post('/countings/rounds/{round}/close', [CountingController::class, 'closeRound'])->name('countings.rounds.close')->middleware('permission:manage_countings');
Route::post('/countings/{counting}/close', [CountingController::class, 'close'])->name('countings.close')->middleware('permission:manage_countings');
Route::get('/countings/{counting}/products', [CountingController::class, 'products'])->name('countings.products')->middleware('permission:capture_countings|view_countings');

// Captura en campo (capturador, JSON para la PWA)
Route::get('/my/counting-rounds', [RoundCaptureController::class, 'myRounds'])->name('countings.my-rounds')->middleware('permission:capture_countings');
Route::get('/counting-rounds/{round}', [RoundCaptureController::class, 'show'])->name('countings.rounds.show')->middleware('permission:capture_countings');
Route::post('/counting-rounds/{round}/capture', [RoundCaptureController::class, 'capture'])->name('countings.rounds.capture')->middleware('permission:capture_countings');
Route::post('/counting-rounds/{round}/finish', [RoundCaptureController::class, 'finish'])->name('countings.rounds.finish')->middleware('permission:capture_countings');
