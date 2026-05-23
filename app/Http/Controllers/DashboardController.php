<?php

namespace App\Http\Controllers;

use App\Models\MonthlyLedger;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $ledger = MonthlyLedger::with('entries')
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();

        $recentLedgers = MonthlyLedger::orderByDesc('year')
            ->orderByDesc('month')
            ->limit(6)
            ->get();

        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        return view('dashboard', compact('ledger', 'recentLedgers', 'pharmacyName', 'currency'));
    }
}
