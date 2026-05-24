<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $supplier->name }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; margin: 24px; }
        h1, h2, h3, p { margin: 0; }
        .header { margin-bottom: 20px; text-align: center; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header h2 { font-size: 14px; margin-bottom: 4px; }
        .meta-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .meta-table td { border: 1px solid #d1d5db; padding: 8px 10px; vertical-align: top; }
        .data-table th, .data-table td { border: 1px solid #d1d5db; padding: 7px 8px; }
        .data-table th { background: #f3f4f6; font-size: 10px; text-transform: uppercase; }
        .section-title { font-size: 13px; font-weight: bold; margin: 18px 0 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #6b7280; }
        .summary-grid td { width: 25%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ mb_strtoupper($pharmacyName) }}</h1>
        <h2>SUPPLIER DETAIL REPORT</h2>
        <p class="muted">Generated on {{ now()->format('d M Y h:i A') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td>
                <strong>Supplier Name</strong><br>
                {{ $supplier->name }}
            </td>
            <td>
                <strong>Phone</strong><br>
                {{ $supplier->phone ?: 'N/A' }}
            </td>
            <td>
                <strong>Email</strong><br>
                {{ $supplier->email ?: 'N/A' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Address</strong><br>
                {{ $supplier->address ?: 'N/A' }}
            </td>
            <td>
                <strong>Current Due</strong><br>
                {{ $currency }} {{ number_format($supplier->total_due, 2) }}
            </td>
        </tr>
    </table>

    <table class="meta-table summary-grid">
        <tr>
            <td>
                <strong>Total Purchases</strong><br>
                {{ $currency }} {{ number_format($purchasesTotal, 2) }}
            </td>
            <td>
                <strong>Total Paid In Purchases</strong><br>
                {{ $currency }} {{ number_format($purchasesPaid, 2) }}
            </td>
            <td>
                <strong>Total Payment Records</strong><br>
                {{ $currency }} {{ number_format($paymentsTotal, 2) }}
            </td>
            <td>
                <strong>Total Due</strong><br>
                {{ $currency }} {{ number_format($purchasesDue, 2) }}
            </td>
        </tr>
    </table>

    <div class="section-title">Purchase List</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Voucher</th>
                <th>Details</th>
                <th class="text-right">Total</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($supplier->purchases as $purchase)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                    <td>{{ $purchase->voucher_no ?: 'N/A' }}</td>
                    <td>{{ $purchase->details ?: '-' }}</td>
                    <td class="text-right">{{ number_format($purchase->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($purchase->paid_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($purchase->due_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center muted">No purchases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Payment List</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Applied To</th>
                <th>Details</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($supplier->payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}</td>
                    <td>{{ $payment->purchase?->voucher_no ?: 'General Balance' }}</td>
                    <td>{{ $payment->details ?: '-' }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center muted">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
