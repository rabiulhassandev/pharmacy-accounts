<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Setting;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // 1. Total Purchase (monthly & today)
        $purchaseToday = (float) Purchase::whereDate('date', $today)->sum('total_amount');
        $purchaseMonthly = (float) Purchase::whereMonth('date', $thisMonth)->whereYear('date', $thisYear)->sum('total_amount');

        // 2. Supplier Due (monthly & today)
        $supplierDueToday = (float) Purchase::whereDate('date', $today)->sum('due_amount');
        $supplierDueMonthly = (float) Purchase::whereMonth('date', $thisMonth)->whereYear('date', $thisYear)->sum('due_amount');

        // 3. Sale Amount (monthly & today)
        $saleToday = (float) Sale::whereDate('date', $today)->sum('total_amount');
        $saleMonthly = (float) Sale::whereMonth('date', $thisMonth)->whereYear('date', $thisYear)->sum('total_amount');

        // 4. Customer Due (monthly & today)
        $customerDueToday = (float) Sale::whereDate('date', $today)->sum('due_amount');
        $customerDueMonthly = (float) Sale::whereMonth('date', $thisMonth)->whereYear('date', $thisYear)->sum('due_amount');

        // 5. Total Expenses (monthly & today)
        $expenseToday = (float) Expense::whereDate('datetime', $today)->sum('amount');
        $expenseMonthly = (float) Expense::whereMonth('datetime', $thisMonth)->whereYear('datetime', $thisYear)->sum('amount');

        // 6. Current Cash (all-time)
        // Cash In = initial payments on sales + subsequent general sales payments
        $cashIn = (float) Sale::sum('paid_amount') + (float) SalePayment::whereNull('sale_id')->sum('amount');
        // Cash Out = initial payments on purchases + subsequent general purchase payments + expenses
        $cashOut = (float) Purchase::sum('paid_amount') + (float) PurchasePayment::whereNull('purchase_id')->sum('amount') + (float) Expense::sum('amount');
        $currentCash = $cashIn - $cashOut;

        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        return view('dashboard', compact(
            'purchaseToday',
            'purchaseMonthly',
            'supplierDueToday',
            'supplierDueMonthly',
            'saleToday',
            'saleMonthly',
            'customerDueToday',
            'customerDueMonthly',
            'expenseToday',
            'expenseMonthly',
            'currentCash',
            'pharmacyName',
            'currency'
        ));
    }
}
