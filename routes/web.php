<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MonthManagerController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Month Manager
    Route::get('/months', [MonthManagerController::class, 'index'])->name('months.index');
    Route::post('/months', [MonthManagerController::class, 'store'])->name('months.store');
    Route::patch('/months/{ledger}/lock', [MonthManagerController::class, 'lock'])->name('months.lock');
    Route::delete('/months/{ledger}', [MonthManagerController::class, 'destroy'])->name('months.destroy');

    // Ledger
    Route::get('/ledger/{ledger}', [LedgerController::class, 'show'])->name('ledger.show');
    Route::post('/ledger/{ledger}/entries', [LedgerController::class, 'storeEntry'])->name('ledger.entries.store');
    Route::patch('/ledger/entries/{entry}', [LedgerController::class, 'updateEntry'])->name('ledger.entries.update');

    // Exports
    Route::get('/export/{ledger}/excel', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/export/{ledger}/pdf', [ExportController::class, 'pdf'])->name('export.pdf');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
});
