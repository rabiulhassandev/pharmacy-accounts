<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PurchasePayment extends Model {
    use HasFactory;
    protected $fillable = ['purchase_id', 'supplier_id', 'date', 'amount', 'details'];
    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
}