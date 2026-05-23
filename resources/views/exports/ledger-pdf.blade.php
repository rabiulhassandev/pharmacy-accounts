<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $ledger->name }} Returns</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14px; }
        .header h2 { margin: 0; font-size: 12px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #000; padding: 2px 1px; text-align: center; }
        th { background-color: #f0f0f0; }
        .total-row { font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ mb_strtoupper($pharmacyName) }}</h1>
        <h2>MONTHLY ACCOUNTS SUMMARY {{ mb_strtoupper($ledger->name) }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                <th colspan="3">Purchases</th>
                <th colspan="4">Payments</th>
                <th colspan="5">Sales</th>
                <th colspan="5">Costs</th>
                <th colspan="3">Cash Flow</th>
            </tr>
            <tr>
                <th>Med. Pur. Company</th>
                <th>Med. Pur. Shop</th>
                <th>Med. Pur. Other</th>
                <th>Payment Company</th>
                <th>Payment Shop</th>
                <th>Payment Other</th>
                <th>Total Payment</th>
                <th>Daily Sale</th>
                <th>Hole Sale</th>
                <th>Other Sale</th>
                <th>Due Purchase</th>
                <th>Due Sale</th>
                <th>Daily Staff Cost</th>
                <th>Other Cost</th>
                <th>Salary</th>
                <th>Bill</th>
                <th>Rent</th>
                <th>Cash</th>
                <th>Total</th>
                <th>Prev. Balance</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totals = array_fill_keys(['mpc', 'mps', 'mpo', 'pc', 'ps', 'po', 'tp', 'ds', 'hs', 'os', 'dp', 'dus', 'dsc', 'oc', 's', 'b', 'r', 'c'], 0);
            @endphp
            @foreach($rows as $row)
                @php
                    $entry = $row['entry'];
                    if ($entry) {
                        $totals['mpc'] += $entry->medicine_purchase_company;
                        $totals['mps'] += $entry->medicine_purchase_shop;
                        $totals['mpo'] += $entry->medicine_purchase_other;
                        $totals['pc'] += $entry->payment_company;
                        $totals['ps'] += $entry->payment_shop;
                        $totals['po'] += $entry->payment_other;
                        $totals['tp'] += $entry->total_payment;
                        $totals['ds'] += $entry->daily_sale;
                        $totals['hs'] += $entry->hole_sale;
                        $totals['os'] += $entry->other_sale;
                        $totals['dp'] += $entry->due_purchase;
                        $totals['dus'] += $entry->due_sale;
                        $totals['dsc'] += $entry->daily_staff_cost;
                        $totals['oc'] += $entry->other_cost;
                        $totals['s'] += $entry->salary;
                        $totals['b'] += $entry->bill;
                        $totals['r'] += $entry->rent;
                        $totals['c'] += $entry->cash;
                    }
                @endphp
                <tr>
                    <td>{{ $row['date']->format('d-M-y') }}</td>
                    <td class="text-right">{{ $entry && $entry->medicine_purchase_company > 0 ? (float)$entry->medicine_purchase_company : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->medicine_purchase_shop > 0 ? (float)$entry->medicine_purchase_shop : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->medicine_purchase_other > 0 ? (float)$entry->medicine_purchase_other : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->payment_company > 0 ? (float)$entry->payment_company : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->payment_shop > 0 ? (float)$entry->payment_shop : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->payment_other > 0 ? (float)$entry->payment_other : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->total_payment > 0 ? (float)$entry->total_payment : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->daily_sale > 0 ? (float)$entry->daily_sale : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->hole_sale > 0 ? (float)$entry->hole_sale : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->other_sale > 0 ? (float)$entry->other_sale : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->due_purchase > 0 ? (float)$entry->due_purchase : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->due_sale > 0 ? (float)$entry->due_sale : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->daily_staff_cost > 0 ? (float)$entry->daily_staff_cost : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->other_cost > 0 ? (float)$entry->other_cost : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->salary > 0 ? (float)$entry->salary : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->bill > 0 ? (float)$entry->bill : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->rent > 0 ? (float)$entry->rent : '' }}</td>
                    <td class="text-right">{{ $entry && $entry->cash != 0 ? (float)$entry->cash : '' }}</td>
                    <td class="text-right">{{ (float)$row['runningBalance'] }}</td>
                    <td class="text-right">{{ (float)$row['prevBalance'] }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL=</td>
                <td class="text-right">{{ (float)$totals['mpc'] }}</td>
                <td class="text-right">{{ (float)$totals['mps'] }}</td>
                <td class="text-right">{{ (float)$totals['mpo'] }}</td>
                <td class="text-right">{{ (float)$totals['pc'] }}</td>
                <td class="text-right">{{ (float)$totals['ps'] }}</td>
                <td class="text-right">{{ (float)$totals['po'] }}</td>
                <td class="text-right">{{ (float)$totals['tp'] }}</td>
                <td class="text-right">{{ (float)$totals['ds'] }}</td>
                <td class="text-right">{{ (float)$totals['hs'] }}</td>
                <td class="text-right">{{ (float)$totals['os'] }}</td>
                <td class="text-right">{{ (float)$totals['dp'] }}</td>
                <td class="text-right">{{ (float)$totals['dus'] }}</td>
                <td class="text-right">{{ (float)$totals['dsc'] }}</td>
                <td class="text-right">{{ (float)$totals['oc'] }}</td>
                <td class="text-right">{{ (float)$totals['s'] }}</td>
                <td class="text-right">{{ (float)$totals['b'] }}</td>
                <td class="text-right">{{ (float)$totals['r'] }}</td>
                <td class="text-right">{{ (float)$totals['c'] }}</td>
                <td class="text-right">{{ (float)($ledger->previous_balance + $totals['c']) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
