<?php

namespace App\Http\Controllers;

use App\Models\MonthlyLedger;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function excel(MonthlyLedger $ledger): Response
    {
        $ledger->load('entries');
        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($ledger->name);

        // Title rows
        $sheet->mergeCells('A1:V1');
        $sheet->setCellValue('A1', $pharmacyName);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:V2');
        $sheet->setCellValue('A2', 'MONTHLY ACCOUNTS SUMMARY '.strtoupper($ledger->name));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header row
        $headers = [
            'Date', 'Med. Purchase Company', 'Med. Purchase Shop', 'Med. Purchase Other',
            'Payment Company', 'Payment Shop', 'Payment Other', 'Total Payment',
            'Daily Sale', 'Hole Sale', 'Other Sale', 'Due Purchase', 'Due Sale',
            'Daily Staff Cost', 'Other Cost', 'Salary', 'Bill', 'Rent',
            'Cash', 'Total', 'Prev. Balance',
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col.'3', $header);
            $sheet->getStyle($col.'3')->applyFromArray([
                'font' => ['bold' => true, 'size' => 9],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            ]);
            $col++;
        }

        // Data rows
        $daysInMonth = Carbon::createFromDate($ledger->year, $ledger->month, 1)->daysInMonth;
        $entriesByDate = $ledger->entries->keyBy(fn ($e) => $e->entry_date->format('Y-m-d'));
        $runningBalance = (float) $ledger->previous_balance;
        $row = 4;

        $totals = array_fill(0, 20, 0);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($ledger->year, $ledger->month, $day);
            $key = $date->format('Y-m-d');
            $entry = $entriesByDate->get($key);

            $prevBalance = $runningBalance;
            $cash = $entry ? $entry->cash : 0.0;
            $totalPayment = $entry ? $entry->total_payment : 0.0;
            $runningBalance += $cash;

            $values = [
                $date->format('d-M-y'),
                $entry ? (float) $entry->medicine_purchase_company : '',
                $entry ? (float) $entry->medicine_purchase_shop : '',
                $entry ? (float) $entry->medicine_purchase_other : '',
                $entry ? (float) $entry->payment_company : '',
                $entry ? (float) $entry->payment_shop : '',
                $entry ? (float) $entry->payment_other : '',
                $entry ? $totalPayment : '',
                $entry ? (float) $entry->daily_sale : '',
                $entry ? (float) $entry->hole_sale : '',
                $entry ? (float) $entry->other_sale : '',
                $entry ? (float) $entry->due_purchase : '',
                $entry ? (float) $entry->due_sale : '',
                $entry ? (float) $entry->daily_staff_cost : '',
                $entry ? (float) $entry->other_cost : '',
                $entry ? (float) $entry->salary : '',
                $entry ? (float) $entry->bill : '',
                $entry ? (float) $entry->rent : '',
                $entry ? $cash : '',
                $runningBalance,
                $prevBalance,
            ];

            $col = 'A';
            foreach ($values as $i => $val) {
                $sheet->setCellValue($col.$row, $val);
                if ($i > 0 && is_numeric($val) && $val != '') {
                    $totals[$i] = ($totals[$i] ?? 0) + (float) $val;
                }
                $col++;
            }
            $row++;
        }

        // Totals row
        $sheet->setCellValue('A'.$row, 'TOTAL=');
        $sheet->getStyle('A'.$row)->getFont()->setBold(true);
        $col = 'B';
        for ($i = 1; $i <= 20; $i++) {
            $sheet->setCellValue($col.$row, $totals[$i] ?? '');
            $col++;
        }
        $sheet->getStyle('A'.$row.':U'.$row)->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border all data
        $sheet->getStyle('A3:U'.$row)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
            ],
        ]);

        $writer = new Xlsx($spreadsheet);
        $filename = 'ledger_'.$ledger->year.'_'.$ledger->month.'.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function pdf(MonthlyLedger $ledger): Response
    {
        $ledger->load('entries');
        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        $daysInMonth = Carbon::createFromDate($ledger->year, $ledger->month, 1)->daysInMonth;
        $entriesByDate = $ledger->entries->keyBy(fn ($e) => $e->entry_date->format('Y-m-d'));

        $runningBalance = (float) $ledger->previous_balance;
        $rows = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($ledger->year, $ledger->month, $day);
            $key = $date->format('Y-m-d');
            $entry = $entriesByDate->get($key);
            $prevBalance = $runningBalance;
            $cash = $entry ? $entry->cash : 0.0;
            $runningBalance += $cash;
            $rows[] = compact('date', 'entry', 'prevBalance', 'runningBalance', 'cash');
        }

        $pdf = Pdf::loadView('exports.ledger-pdf', compact('ledger', 'rows', 'pharmacyName', 'currency'))
            ->setPaper('a3', 'landscape');

        return $pdf->download('ledger_'.$ledger->year.'_'.$ledger->month.'.pdf');
    }
}
