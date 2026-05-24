<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $unpaidPurchases = $supplier->purchases()->where('due_amount', '>', 0)->orderByDesc('date')->get();
        $purchases = $supplier->purchases()->orderByDesc('date')->paginate(5, ['*'], 'purchases_page');
        $payments = $supplier->payments()->orderByDesc('date')->paginate(5, ['*'], 'payments_page');
        $currency = Setting::get('currency_symbol', 'à§³');

        return view('suppliers.show', compact('supplier', 'unpaidPurchases', 'purchases', 'payments', 'currency'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0 || $supplier->payments()->count() > 0) {
            return back()->withErrors('Cannot delete a supplier that has associated purchase or payment history.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }

    public function storePurchase(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'voucher_no' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);
        $due = $validated['total_amount'] - $validated['paid_amount'];
        $supplier->purchases()->create(array_merge($validated, ['due_amount' => $due]));

        $supplier->increment('total_due', $due);

        return back()->with('success', 'Purchase added.');
    }

    public function storePayment(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'purchase_id' => 'nullable|exists:purchases,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);
        $validated['supplier_id'] = $supplier->id;
        PurchasePayment::create($validated);

        if (! empty($validated['purchase_id'])) {
            $purchase = Purchase::find($validated['purchase_id']);
            $purchase->increment('paid_amount', $validated['amount']);
            $purchase->decrement('due_amount', $validated['amount']);
        }
        $supplier->decrement('total_due', $validated['amount']);

        return back()->with('success', 'Payment recorded.');
    }
}
