<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SalePayment extends Model {
    use HasFactory;
    protected $fillable = ['sale_id', 'customer_id', 'date', 'amount', 'details'];
    public function sale() { return $this->belongsTo(Sale::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}