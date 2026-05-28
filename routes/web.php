<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Exports
    Route::get('/customers/{customer}/report/pdf', [ExportController::class, 'customerPdf'])->name('customers.report.pdf');
    Route::get('/suppliers/{supplier}/report/pdf', [ExportController::class, 'supplierPdf'])->name('suppliers.report.pdf');
    Route::get('/report/monthly', [ExportController::class, 'monthlyReportPdf'])->name('reports.monthly.pdf');

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

    // Expenses
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);
});
