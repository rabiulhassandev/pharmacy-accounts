<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'voucher_no', 'category', 'details', 'total_amount', 'paid_amount', 'due_amount'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }
}
