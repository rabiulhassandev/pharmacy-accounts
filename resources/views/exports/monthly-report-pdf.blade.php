<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Monthly Accounts Report - {{ $monthName }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; margin: 20px; line-height: 1.4; }
        h1, h2, h3, h4, p { margin: 0; }
        .header { margin-bottom: 25px; text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 10px; }
        .header h1 { font-size: 20px; color: #065f46; margin-bottom: 4px; font-weight: bold; }
        .header h2 { font-size: 13px; color: #374151; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { color: #6b7280; font-size: 9px; }
        
        .section-title { font-size: 12px; font-weight: bold; color: #065f46; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin: 20px 0 8px 0; text-transform: uppercase; }
        
        .table-layout { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-layout th, .table-layout td { border: 1px solid #d1d5db; padding: 6px 8px; vertical-align: middle; }
        .table-layout th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; color: #374151; font-weight: bold; }
        
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table td { border: 1px solid #cbd5e1; padding: 10px; width: 25%; vertical-align: top; }
        .summary-title { font-size: 8px; text-transform: uppercase; color: #64748b; font-weight: bold; margin-bottom: 4px; }
        .summary-value { font-size: 14px; font-weight: bold; color: #0f172a; }
        .summary-value.positive { color: #047857; }
        .summary-value.negative { color: #be123c; }
        .summary-value.neutral { color: #0369a1; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .muted { color: #9ca3af; }
        
        .analytics-container { width: 100%; margin-top: 15px; }
        .analytics-col { width: 50%; vertical-align: top; }
        .analytics-card { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; background-color: #f8fafc; margin-right: 10px; }
        .analytics-card-right { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; background-color: #f8fafc; margin-left: 10px; }
        
        .progress-bar-bg { background-color: #e2e8f0; height: 8px; border-radius: 4px; width: 100%; margin-top: 4px; }
        .progress-bar-fill { background-color: #10b981; height: 8px; border-radius: 4px; }
        .progress-bar-fill.expense { background-color: #f97316; }
        
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ mb_strtoupper($pharmacyName) }}</h1>
        <h2>Monthly Financial Performance Report</h2>
        <p>Report Period: <strong>{{ mb_strtoupper($monthName) }}</strong> &nbsp;|&nbsp; Generated on: {{ now()->format('d M Y h:i A') }}</p>
    </div>

    <!-- Financial Summary Grid -->
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-title">Total Sales Revenue</div>
                <div class="summary-value neutral">{{ $currency }} {{ number_format($totalSalesAmount, 2) }}</div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">{{ $salesCount }} sales invoices</p>
            </td>
            <td>
                <div class="summary-title">Cost of Purchases</div>
                <div class="summary-value negative">{{ $currency }} {{ number_format($totalPurchasesAmount, 2) }}</div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">{{ $purchasesCount }} purchase vouchers</p>
            </td>
            <td>
                <div class="summary-title">Operating Expenses</div>
                <div class="summary-value negative">{{ $currency }} {{ number_format($totalExpensesAmount, 2) }}</div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">{{ $expensesCount }} transactions</p>
            </td>
            <td>
                <div class="summary-title">Net Profit / Loss</div>
                <div class="summary-value {{ $netProfitLoss >= 0 ? 'positive' : 'negative' }}">
                    {{ $currency }} {{ number_format($netProfitLoss, 2) }}
                </div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">Sales - Cost - Expenses</p>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-title">Actual Cash Collected</div>
                <div class="summary-value positive">{{ $currency }} {{ number_format($totalCashIn, 2) }}</div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">Sales paid + payments</p>
            </td>
            <td>
                <div class="summary-title">Actual Cash Paid Out</div>
                <div class="summary-value negative">{{ $currency }} {{ number_format($totalCashOut, 2) }}</div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">Purchases + payments + cost</p>
            </td>
            <td>
                <div class="summary-title">Net Cash Flow</div>
                <div class="summary-value {{ $netCashFlow >= 0 ? 'positive' : 'negative' }}">
                    {{ $currency }} {{ number_format($netCashFlow, 2) }}
                </div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">Liquidity surplus / deficit</p>
            </td>
            <td>
                <div class="summary-title">Dues Created (Net)</div>
                <div class="summary-value negative">
                    +{{ $currency }} {{ number_format($outstandingCustomerDueCreated, 2) }}
                </div>
                <p class="muted" style="margin: 4px 0 0 0; font-size: 8px;">Uncollected customer sales</p>
            </td>
        </tr>
    </table>

    <!-- Detailed Sales List -->
    <div class="section-title">Sales Transactions (Detailed)</div>
    <table class="table-layout">
        <thead>
            <tr>
                <th style="width: 15%">Date</th>
                <th style="width: 15%">Invoice No</th>
                <th style="width: 25%">Customer</th>
                <th style="width: 15%" class="text-right">Total Amount</th>
                <th style="width: 15%" class="text-right">Paid Amount</th>
                <th style="width: 15%" class="text-right">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                    <td class="text-center font-bold">{{ $sale->invoice_no ?: 'N/A' }}</td>
                    <td>{{ $sale->customer?->name ?: 'Walk-in Customer' }}</td>
                    <td class="text-right">{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->paid_amount, 2) }}</td>
                    <td class="text-right font-bold {{ $sale->due_amount > 0 ? 'negative' : '' }}" style="color: {{ $sale->due_amount > 0 ? '#b91c1c' : '' }};">{{ number_format($sale->due_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center muted">No sales transactions recorded in this period.</td>
                </tr>
            @endforelse
            @if ($sales->isNotEmpty())
                <tr style="background-color: #f9fafb; font-weight: bold;">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right">{{ number_format($totalSalesAmount, 2) }}</td>
                    <td class="text-right">{{ number_format($sales->sum('paid_amount'), 2) }}</td>
                    <td class="text-right" style="color: #b91c1c;">{{ number_format($outstandingCustomerDueCreated, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Page Break for cleaner layouts if transaction lists are long or for the analytics page -->
    <div class="page-break"></div>

    <!-- Detailed Purchases List -->
    <div class="section-title">Purchase Transactions (Detailed)</div>
    <table class="table-layout">
        <thead>
            <tr>
                <th style="width: 15%">Date</th>
                <th style="width: 15%">Voucher No</th>
                <th style="width: 25%">Supplier</th>
                <th style="width: 15%" class="text-right">Total Amount</th>
                <th style="width: 15%" class="text-right">Paid Amount</th>
                <th style="width: 15%" class="text-right">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                    <td class="text-center font-bold">{{ $purchase->voucher_no ?: 'N/A' }}</td>
                    <td>{{ $purchase->supplier?->name ?: 'General Supplier' }}</td>
                    <td class="text-right">{{ number_format($purchase->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($purchase->paid_amount, 2) }}</td>
                    <td class="text-right font-bold {{ $purchase->due_amount > 0 ? 'negative' : '' }}" style="color: {{ $purchase->due_amount > 0 ? '#b91c1c' : '' }};">{{ number_format($purchase->due_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center muted">No purchase transactions recorded in this period.</td>
                </tr>
            @endforelse
            @if ($purchases->isNotEmpty())
                <tr style="background-color: #f9fafb; font-weight: bold;">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right">{{ number_format($totalPurchasesAmount, 2) }}</td>
                    <td class="text-right">{{ number_format($purchases->sum('paid_amount'), 2) }}</td>
                    <td class="text-right" style="color: #b91c1c;">{{ number_format($outstandingSupplierDueIncurred, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Detailed Expenses List -->
    <div class="section-title">Operating Expenses List</div>
    <table class="table-layout">
        <thead>
            <tr>
                <th style="width: 20%">Date & Time</th>
                <th style="width: 35%">Expense Title</th>
                <th style="width: 25%">Cost Type</th>
                <th style="width: 20%" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $expense)
                <tr>
                    <td class="text-center">{{ $expense->datetime ? $expense->datetime->format('d M Y h:i A') : 'N/A' }}</td>
                    <td>{{ $expense->title }}</td>
                    <td class="text-center font-bold" style="color: #4b5563;">{{ $expense->cost_type }}</td>
                    <td class="text-right font-bold">{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center muted">No expense transactions recorded in this period.</td>
                </tr>
            @endforelse
            @if ($expenses->isNotEmpty())
                <tr style="background-color: #f9fafb; font-weight: bold;">
                    <td colspan="3" class="text-right">Total Expenses:</td>
                    <td class="text-right">{{ number_format($totalExpensesAmount, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Page Break for Analytics -->
    <div class="page-break"></div>

    <!-- Analytics Section -->
    <div class="section-title">Accounts Analytics & Key Performance Indicators (KPIs)</div>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Sales Analytics -->
            <td class="analytics-col">
                <div class="analytics-card">
                    <h3 style="font-size: 11px; font-weight: bold; color: #065f46; margin-bottom: 8px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Sales Category Breakdown</h3>
                    <table style="width: 100%; font-size: 9px;">
                        <thead>
                            <tr style="border-bottom: 1px solid #e2e8f0; text-align: left;">
                                <th style="padding: 4px 0;">Category</th>
                                <th style="padding: 4px 0; text-align: center;">Count</th>
                                <th style="padding: 4px 0; text-align: right;">Total Amount</th>
                                <th style="padding: 4px 0; text-align: right;">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salesByCategory as $category => $data)
                                @php
                                    $percentage = $totalSalesAmount > 0 ? ($data['total'] / $totalSalesAmount) * 100 : 0;
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold;">{{ $category }}</td>
                                    <td style="padding: 6px 0; text-align: center;">{{ $data['count'] }}</td>
                                    <td style="padding: 6px 0; text-align: right;">{{ $currency }} {{ number_format($data['total'], 2) }}</td>
                                    <td style="padding: 6px 0; text-align: right; font-weight: bold; color: #047857;">{{ number_format($percentage, 1) }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding-bottom: 4px;">
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #9ca3af; padding: 10px 0;">No sales data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>

            <!-- Expenses Analytics -->
            <td class="analytics-col">
                <div class="analytics-card-right">
                    <h3 style="font-size: 11px; font-weight: bold; color: #9a3412; margin-bottom: 8px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Expenses breakdown</h3>
                    <table style="width: 100%; font-size: 9px;">
                        <thead>
                            <tr style="border-bottom: 1px solid #e2e8f0; text-align: left;">
                                <th style="padding: 4px 0;">Cost Type</th>
                                <th style="padding: 4px 0; text-align: center;">Count</th>
                                <th style="padding: 4px 0; text-align: right;">Total Amount</th>
                                <th style="padding: 4px 0; text-align: right;">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expensesByCostType as $costType => $data)
                                @php
                                    $percentage = $totalExpensesAmount > 0 ? ($data['total'] / $totalExpensesAmount) * 100 : 0;
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 0; font-weight: bold;">{{ $costType }}</td>
                                    <td style="padding: 6px 0; text-align: center;">{{ $data['count'] }}</td>
                                    <td style="padding: 6px 0; text-align: right;">{{ $currency }} {{ number_format($data['total'], 2) }}</td>
                                    <td style="padding: 6px 0; text-align: right; font-weight: bold; color: #ea580c;">{{ number_format($percentage, 1) }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding-bottom: 4px;">
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill expense" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #9ca3af; padding: 10px 0;">No expense data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="width: 100%;">
                <div style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; background-color: #f8fafc;">
                    <h3 style="font-size: 11px; font-weight: bold; color: #0f172a; margin-bottom: 8px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Efficiency & Accounts Diagnostics</h3>
                    <table style="width: 100%; font-size: 10px;">
                        <tr>
                            <td style="width: 50%; padding: 4px 0;">
                                <strong>Average Invoice Value:</strong>
                            </td>
                            <td style="width: 50%; padding: 4px 0; text-align: right;" class="font-bold">
                                {{ $currency }} {{ number_format($averageSaleValue, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">
                                <strong>Average Supplier Purchase Value:</strong>
                            </td>
                            <td style="padding: 4px 0; text-align: right;" class="font-bold">
                                {{ $currency }} {{ number_format($averagePurchaseValue, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">
                                <strong>Sales Collection Rate:</strong>
                                <span class="muted" style="font-size: 8px; display: block;">Percentage of month's sales collected instantly</span>
                            </td>
                            <td style="padding: 4px 0; text-align: right;" class="font-bold {{ $salesCollectedRate >= 80 ? 'positive' : 'negative' }}" style="color: {{ $salesCollectedRate >= 80 ? '#047857' : '#b91c1c' }};">
                                {{ number_format($salesCollectedRate, 1) }}%
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 4px;">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $salesCollectedRate }}%; background-color: {{ $salesCollectedRate >= 80 ? '#10b981' : '#f59e0b' }}"></div>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #e2e8f0;">
                            <td style="padding: 8px 0 4px 0;">
                                <strong>Operational Cost Ratio (OCR):</strong>
                                <span class="muted" style="font-size: 8px; display: block;">Expenses divided by Sales Revenue</span>
                            </td>
                            <td style="padding: 8px 0 4px 0; text-align: right; font-weight: bold;">
                                @php
                                    $ocr = $totalSalesAmount > 0 ? ($totalExpensesAmount / $totalSalesAmount) * 100 : 0.0;
                                @endphp
                                {{ number_format($ocr, 1) }}%
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">
                                <strong>Liquidity Health Margin:</strong>
                                <span class="muted" style="font-size: 8px; display: block;">Net Cash Flow / Total Revenue</span>
                            </td>
                            <td style="padding: 4px 0; text-align: right; font-weight: bold; color: {{ $netCashFlow >= 0 ? '#047857' : '#b91c1c' }};">
                                @php
                                    $lhm = $totalSalesAmount > 0 ? ($netCashFlow / $totalSalesAmount) * 100 : 0.0;
                                @endphp
                                {{ number_format($lhm, 1) }}%
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
