<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MonthManagerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
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
    Route::get('/customers/{customer}/report/pdf', [ExportController::class, 'customerPdf'])->name('customers.report.pdf');
    Route::get('/suppliers/{supplier}/report/pdf', [ExportController::class, 'supplierPdf'])->name('suppliers.report.pdf');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::post('suppliers/{supplier}/purchases', [SupplierController::class, 'storePurchase'])->name('suppliers.purchases.store');
    Route::post('suppliers/{supplier}/payments', [SupplierController::class, 'storePayment'])->name('suppliers.payments.store');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/sales', [CustomerController::class, 'storeSale'])->name('customers.sales.store');
    Route::post('customers/{customer}/payments', [CustomerController::class, 'storePayment'])->name('customers.payments.store');
});
