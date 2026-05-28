<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::orderByDesc('datetime')->paginate(10);
        $currency = Setting::get('currency_symbol', '৳');

        $costTypes = [
            'Daily Staff Cost',
            'Transportation',
            'Salary',
            'Bill',
            'Rent',
            'Other',
        ];

        return view('expenses.index', compact('expenses', 'currency', 'costTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'cost_type' => ['required', 'string', 'in:Daily Staff Cost,Transportation,Trasportation,Salary,Bill,Rent,Other'],
            'datetime' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        // Standardize cost type spelling if 'Trasportation' is entered
        if ($validated['cost_type'] === 'Trasportation') {
            $validated['cost_type'] = 'Transportation';
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
