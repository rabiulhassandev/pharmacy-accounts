<?php

namespace App\Models;

use Database\Factories\LedgerEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'monthly_ledger_id', 'entry_date',
    'medicine_purchase_company', 'medicine_purchase_shop', 'medicine_purchase_other',
    'payment_company', 'payment_shop', 'payment_other',
    'daily_sale', 'hole_sale', 'other_sale', 'due_purchase', 'due_sale',
    'daily_staff_cost', 'other_cost', 'salary', 'bill', 'rent',
])]
class LedgerEntry extends Model
{
    /** @use HasFactory<LedgerEntryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'medicine_purchase_company' => 'decimal:2',
            'medicine_purchase_shop' => 'decimal:2',
            'medicine_purchase_other' => 'decimal:2',
            'payment_company' => 'decimal:2',
            'payment_shop' => 'decimal:2',
            'payment_other' => 'decimal:2',
            'daily_sale' => 'decimal:2',
            'hole_sale' => 'decimal:2',
            'other_sale' => 'decimal:2',
            'due_purchase' => 'decimal:2',
            'due_sale' => 'decimal:2',
            'daily_staff_cost' => 'decimal:2',
            'other_cost' => 'decimal:2',
            'salary' => 'decimal:2',
            'bill' => 'decimal:2',
            'rent' => 'decimal:2',
        ];
    }

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(MonthlyLedger::class, 'monthly_ledger_id');
    }

    public function getTotalPaymentAttribute(): float
    {
        return (float) $this->payment_company + (float) $this->payment_shop + (float) $this->payment_other;
    }

    public function getTotalSalesAttribute(): float
    {
        return (float) $this->daily_sale + (float) $this->hole_sale + (float) $this->other_sale + (float) $this->due_sale;
    }

    public function getTotalCostsAttribute(): float
    {
        return $this->total_payment
            + (float) $this->daily_staff_cost
            + (float) $this->other_cost
            + (float) $this->salary
            + (float) $this->bill
            + (float) $this->rent;
    }

    /** Daily cash = sales - costs */
    public function getCashAttribute(): float
    {
        return $this->total_sales - $this->total_costs;
    }
}
