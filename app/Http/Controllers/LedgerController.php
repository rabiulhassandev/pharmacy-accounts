<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerEntryRequest;
use App\Models\LedgerEntry;
use App\Models\MonthlyLedger;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LedgerController extends Controller
{
    public function show(MonthlyLedger $ledger): View
    {
        $ledger->load('entries');
        $currency = Setting::get('currency_symbol', '৳');
        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');

        // Build a complete map of entry_date => LedgerEntry for all days in the month
        $entriesByDate = $ledger->entries->keyBy(fn ($e) => $e->entry_date->format('Y-m-d'));

        // Generate all days in the month
        $daysInMonth = Carbon::createFromDate($ledger->year, $ledger->month, 1)->daysInMonth;
        $days = collect(range(1, $daysInMonth))->map(function ($day) use ($ledger) {
            return Carbon::createFromDate($ledger->year, $ledger->month, $day);
        });

        // Compute running balance per day
        $runningBalance = (float) $ledger->previous_balance;
        $rows = $days->map(function (Carbon $day) use ($entriesByDate, &$runningBalance) {
            $key = $day->format('Y-m-d');
            $entry = $entriesByDate->get($key);
            $cash = $entry ? $entry->cash : 0.0;
            $prevBalance = $runningBalance;
            $runningBalance += $cash;

            return [
                'date' => $day,
                'entry' => $entry,
                'prev_balance' => $prevBalance,
                'running_total' => $runningBalance,
            ];
        });

        $todayKey = Carbon::today()->format('Y-m-d');

        return view('ledger.show', compact('ledger', 'rows', 'currency', 'pharmacyName', 'todayKey'));
    }

    public function storeEntry(LedgerEntryRequest $request, MonthlyLedger $ledger): JsonResponse
    {
        if ($ledger->is_locked) {
            return response()->json(['error' => 'This month is locked.'], 403);
        }

        $entry = LedgerEntry::updateOrCreate(
            [
                'monthly_ledger_id' => $ledger->id,
                'entry_date' => $request->validated('entry_date'),
            ],
            collect($request->validated())->except('entry_date')->toArray()
        );

        return response()->json([
            'success' => true,
            'entry' => $entry,
            'cash' => $entry->cash,
            'total_payment' => $entry->total_payment,
            'total_sales' => $entry->total_sales,
        ]);
    }

    public function updateEntry(LedgerEntryRequest $request, LedgerEntry $entry): JsonResponse
    {
        if ($entry->ledger->is_locked) {
            return response()->json(['error' => 'This month is locked.'], 403);
        }

        $entry->update($request->validated());
        $entry->refresh();

        return response()->json([
            'success' => true,
            'entry' => $entry,
            'cash' => $entry->cash,
            'total_payment' => $entry->total_payment,
            'total_sales' => $entry->total_sales,
        ]);
    }
}
