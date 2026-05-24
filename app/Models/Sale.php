<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model {
    use HasFactory;
    protected $fillable = ['customer_id', 'date', 'invoice_no', 'details', 'total_amount', 'paid_amount', 'due_amount'];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function payments() { return $this->hasMany(SalePayment::class); }
}