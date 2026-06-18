<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Public\ScanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// QR Code Scan - Public Access (No Login Required)
Route::get('/scan/{kode}', [ScanController::class, 'show'])
    ->name('scan.show')
    ->middleware('throttle:30,1'); // Rate limiting: 30 requests per minute
Route::post('/scan/borrow', [ScanController::class, 'requestBorrow'])
    ->name('scan.borrow')
    ->middleware('throttle:5,1'); // Rate limiting: 5 requests per minute

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Assets
    Route::resource('assets', AssetController::class);
    Route::get('assets/{asset}/print-label', [AssetController::class, 'printLabel'])->name('assets.print-label');
    Route::get('assets-print-labels', [AssetController::class, 'printLabelsBulk'])->name('assets.print-labels-bulk');
    Route::get('assets-scanner', [AssetController::class, 'scanner'])->name('assets.scanner');
    Route::post('assets-scanner/export-pdf', [AssetController::class, 'scannerExportPdf'])->name('assets.scanner.export-pdf');
    Route::post('assets/{asset}/regenerate-qr', [AssetController::class, 'regenerateQr'])->name('assets.regenerate-qr');
    Route::get('assets-preview-code/{category}', [AssetController::class, 'previewCode'])->name('assets.preview-code');
    Route::get('assets-import', [AssetController::class, 'showImport'])->name('assets.import');
    Route::get('assets-template', [AssetController::class, 'downloadTemplate'])->name('assets.download-template');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Borrowings
    Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::post('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'markReturned'])->name('borrowings.return');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Export/Import
    Route::get('export/assets', [ExportController::class, 'exportAssets'])->name('export.assets');
    Route::get('export/borrowings', [ExportController::class, 'exportBorrowings'])->name('export.borrowings');
    Route::post('import/assets/preview', [ExportController::class, 'previewImport'])->name('import.assets.preview');
    Route::post('import/assets/confirm', [ExportController::class, 'confirmImport'])->name('import.assets.confirm');
});

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
});
