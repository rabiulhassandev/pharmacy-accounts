<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['sales', 'payments']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function storeSale(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'invoice_no' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);
        $due = $validated['total_amount'] - $validated['paid_amount'];
        $customer->sales()->create(array_merge($validated, ['due_amount' => $due]));
        
        $customer->increment('total_due', $due);
        return back()->with('success', 'Sale added.');
    }

    public function storePayment(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'sale_id' => 'nullable|exists:sales,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);
        $validated['customer_id'] = $customer->id;
        SalePayment::create($validated);

        if(!empty($validated['sale_id'])) {
            $sale = Sale::find($validated['sale_id']);
            $sale->increment('paid_amount', $validated['amount']);
            $sale->decrement('due_amount', $validated['amount']);
        }
        $customer->decrement('total_due', $validated['amount']);
        return back()->with('success', 'Payment Received.');
    }
}
