<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\MonthlyLedgerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'year', 'month', 'is_locked', 'previous_balance'])]
class MonthlyLedger extends Model
{
    /** @use HasFactory<MonthlyLedgerFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
            'previous_balance' => 'decimal:2',
            'year' => 'integer',
            'month' => 'integer',
        ];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class)->orderBy('entry_date');
    }

    /** Total of all daily_sale + hole_sale + other_sale + due_sale across all entries */
    public function getTotalRevenueAttribute(): float
    {
        return (float) $this->entries->sum(fn (LedgerEntry $e) => $e->daily_sale + $e->hole_sale + $e->other_sale + $e->due_sale
        );
    }

    /** Total of all costs across all entries */
    public function getTotalExpensesAttribute(): float
    {
        return (float) $this->entries->sum(fn (LedgerEntry $e) => $e->payment_company + $e->payment_shop + $e->payment_other
            + $e->daily_staff_cost + $e->other_cost + $e->salary + $e->bill + $e->rent
        );
    }

    /** Net cash = revenue - expenses + previous_balance */
    public function getNetCashAttribute(): float
    {
        return $this->total_revenue - $this->total_expenses + (float) $this->previous_balance;
    }

    /** Generate the month name string from year+month */
    public static function buildName(int $year, int $month): string
    {
        return Carbon::createFromDate($year, $month, 1)->format('F Y');
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }
}
