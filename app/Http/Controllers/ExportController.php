<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function customerPdf(Customer $customer): Response
    {
        $customer->load([
            'sales' => fn ($query) => $query->orderByDesc('date'),
            'payments' => fn ($query) => $query->with('sale')->orderByDesc('date'),
        ]);

        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');
        $salesTotal = (float) $customer->sales->sum('total_amount');
        $salesCollected = (float) $customer->sales->sum('paid_amount');
        $salesDue = (float) $customer->sales->sum('due_amount');
        $paymentsTotal = (float) $customer->payments->sum('amount');
        $filename = 'customer_report_'.($customer->id).'_'.(Str::slug($customer->name) ?: 'customer').'.pdf';

        $pdf = Pdf::loadView('exports.customer-pdf', compact(
            'customer',
            'pharmacyName',
            'currency',
            'salesTotal',
            'salesCollected',
            'salesDue',
            'paymentsTotal',
        ))->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    public function supplierPdf(Supplier $supplier): Response
    {
        $supplier->load([
            'purchases' => fn ($query) => $query->orderByDesc('date'),
            'payments' => fn ($query) => $query->with('purchase')->orderByDesc('date'),
        ]);

        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', 'à§³');
        $purchasesTotal = (float) $supplier->purchases->sum('total_amount');
        $purchasesPaid = (float) $supplier->purchases->sum('paid_amount');
        $purchasesDue = (float) $supplier->purchases->sum('due_amount');
        $paymentsTotal = (float) $supplier->payments->sum('amount');
        $filename = 'supplier_report_'.($supplier->id).'_'.(Str::slug($supplier->name) ?: 'supplier').'.pdf';

        $pdf = Pdf::loadView('exports.supplier-pdf', compact(
            'supplier',
            'pharmacyName',
            'currency',
            'purchasesTotal',
            'purchasesPaid',
            'purchasesDue',
            'paymentsTotal',
        ))->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
