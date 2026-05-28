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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function monthlyReportPdf(Request $request): Response
    {
        $monthInput = $request->query('month');
        if ($monthInput && preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
            $date = Carbon::parse($monthInput.'-01');
        } else {
            $date = Carbon::today();
        }

        $year = $date->year;
        $month = $date->month;
        $monthName = $date->format('F Y');

        $sales = Sale::with('customer')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        $purchases = Purchase::with('supplier')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        $expenses = Expense::whereYear('datetime', $year)
            ->whereMonth('datetime', $month)
            ->orderBy('datetime')
            ->get();

        $totalSalesAmount = (float) $sales->sum('total_amount');
        $salesCount = $sales->count();

        $totalPurchasesAmount = (float) $purchases->sum('total_amount');
        $purchasesCount = $purchases->count();

        $totalExpensesAmount = (float) $expenses->sum('amount');
        $expensesCount = $expenses->count();

        $initialSalesPaid = 0.0;
        foreach ($sales as $sale) {
            $paymentsSum = (float) $sale->payments()->sum('amount');
            $initialPaid = (float) $sale->paid_amount - $paymentsSum;
            $initialSalesPaid += max(0.0, $initialPaid);
        }
        $salePaymentsReceived = (float) SalePayment::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
        $totalCashIn = $initialSalesPaid + $salePaymentsReceived;

        $initialPurchasesPaid = 0.0;
        foreach ($purchases as $purchase) {
            $paymentsSum = (float) $purchase->payments()->sum('amount');
            $initialPaid = (float) $purchase->paid_amount - $paymentsSum;
            $initialPurchasesPaid += max(0.0, $initialPaid);
        }
        $purchasePaymentsMade = (float) PurchasePayment::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
        $totalCashOut = $initialPurchasesPaid + $purchasePaymentsMade + $totalExpensesAmount;

        $netCashFlow = $totalCashIn - $totalCashOut;
        $netProfitLoss = $totalSalesAmount - $totalPurchasesAmount - $totalExpensesAmount;

        $outstandingCustomerDueCreated = (float) $sales->sum('due_amount');
        $outstandingSupplierDueIncurred = (float) $purchases->sum('due_amount');

        $salesByCategory = $sales->groupBy('category')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => (float) $group->sum('total_amount'),
            ];
        });

        $expensesByCostType = $expenses->groupBy('cost_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => (float) $group->sum('amount'),
            ];
        });

        $averageSaleValue = $salesCount > 0 ? $totalSalesAmount / $salesCount : 0.0;
        $averagePurchaseValue = $purchasesCount > 0 ? $totalPurchasesAmount / $purchasesCount : 0.0;

        $salesCollectedRate = $totalSalesAmount > 0
            ? (($totalSalesAmount - $outstandingCustomerDueCreated) / $totalSalesAmount) * 100
            : 100.0;

        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        $filename = 'monthly_report_'.$date->format('Y_m').'.pdf';

        $pdf = Pdf::loadView('exports.monthly-report-pdf', compact(
            'monthName',
            'sales',
            'purchases',
            'expenses',
            'totalSalesAmount',
            'salesCount',
            'totalPurchasesAmount',
            'purchasesCount',
            'totalExpensesAmount',
            'expensesCount',
            'totalCashIn',
            'totalCashOut',
            'netCashFlow',
            'netProfitLoss',
            'outstandingCustomerDueCreated',
            'outstandingSupplierDueIncurred',
            'salesByCategory',
            'expensesByCostType',
            'averageSaleValue',
            'averagePurchaseValue',
            'salesCollectedRate',
            'pharmacyName',
            'currency'
        ))->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
