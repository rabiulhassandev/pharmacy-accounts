<?php

namespace App\Http\Controllers;

use App\Models\MonthlyLedger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonthManagerController extends Controller
{
    public function index(): View
    {
        $ledgers = MonthlyLedger::orderByDesc('year')->orderByDesc('month')->get();

        return view('months.index', compact('ledgers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'previous_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $existing = MonthlyLedger::where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        if ($existing) {
            return back()->withErrors(['month' => 'A ledger for this month already exists.'])->withInput();
        }

        $ledger = MonthlyLedger::create([
            'name' => MonthlyLedger::buildName($validated['year'], $validated['month']),
            'year' => $validated['year'],
            'month' => $validated['month'],
            'previous_balance' => $validated['previous_balance'] ?? 0,
            'is_locked' => false,
        ]);

        return redirect()->route('ledger.show', $ledger)
            ->with('success', "Ledger for {$ledger->name} created successfully.");
    }

    public function lock(MonthlyLedger $ledger): RedirectResponse
    {
        $ledger->update(['is_locked' => ! $ledger->is_locked]);
        $status = $ledger->is_locked ? 'locked' : 'unlocked';

        return back()->with('success', "{$ledger->name} has been {$status}.");
    }

    public function destroy(MonthlyLedger $ledger): RedirectResponse
    {
        $name = $ledger->name;
        $ledger->delete();

        return redirect()->route('months.index')
            ->with('success', "Ledger for {$name} has been deleted.");
    }
}
