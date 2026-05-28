<?php

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\BorrowingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Protected by Laravel Sanctum authentication.
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Assets
    Route::get('/assets', [AssetApiController::class, 'index']);
    Route::get('/assets/{asset}', [AssetApiController::class, 'show']);

    // Borrowings
    Route::get('/borrowings', [BorrowingApiController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingApiController::class, 'show']);
});

// Public API endpoint for QR scan
Route::get('/scan/{kode}', [AssetApiController::class, 'scan']);
