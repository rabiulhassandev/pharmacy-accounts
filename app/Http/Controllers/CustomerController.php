<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::all();

        $currency = Setting::get('currency_symbol', '৳');

        return view('customers.index', compact('customers', 'currency'));
    }

    public function create(): View
    {
        $currency = Setting::get('currency_symbol', '৳');

        return view('customers.create', compact('currency'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $unpaidSales = $customer->sales()
            ->where('due_amount', '>', 0)
            ->orderByDesc('date')
            ->get();

        $sales = $customer->sales()
            ->orderByDesc('date')
            ->paginate(5, ['*'], 'sales_page');

        $payments = $customer->payments()
            ->with('sale')
            ->orderByDesc('date')
            ->paginate(5, ['*'], 'payments_page');

        $hasTransactions = $customer->sales()->exists() || $customer->payments()->exists();

        $currency = Setting::get('currency_symbol', '৳');

        return view('customers.show', compact('customer', 'unpaidSales', 'sales', 'payments', 'hasTransactions', 'currency'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->sales()->exists() || $customer->payments()->exists()) {
            return back()->withErrors('Cannot delete a customer that has associated sale or payment history.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function storeSale(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'details' => ['nullable', 'string'],
        ]);

        if ($validated['paid_amount'] > $validated['total_amount']) {
            return back()->withErrors('Collected amount cannot exceed the sale total amount.')->withInput();
        }

        $dueAmount = $validated['total_amount'] - $validated['paid_amount'];

        DB::transaction(function () use ($customer, $validated, $dueAmount): void {
            $customer->sales()->create([
                ...$validated,
                'due_amount' => $dueAmount,
            ]);

            $customer->increment('total_due', $dueAmount);
        });

        return back()->with('success', 'Sale added.');
    }

    public function storePayment(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'sale_id' => [
                'nullable',
                Rule::exists('sales', 'id')->where(fn ($query) => $query->where('customer_id', $customer->id)),
            ],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'details' => ['nullable', 'string'],
        ]);

        if ($validated['amount'] > $customer->total_due) {
            return back()->withErrors('Received amount cannot exceed the customer due balance.')->withInput();
        }

        $sale = null;

        if (! empty($validated['sale_id'])) {
            $sale = $customer->sales()->find($validated['sale_id']);

            if ($sale instanceof Sale && $validated['amount'] > $sale->due_amount) {
                return back()->withErrors('Received amount cannot exceed the selected sale due amount.')->withInput();
            }
        }

        DB::transaction(function () use ($customer, $validated, $sale): void {
            $customer->payments()->create($validated);

            if ($sale instanceof Sale) {
                $sale->increment('paid_amount', $validated['amount']);
                $sale->decrement('due_amount', $validated['amount']);
            }

            $customer->decrement('total_due', $validated['amount']);
        });

        return back()->with('success', 'Payment received.');
    }
}
