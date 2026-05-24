<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Supplier extends Model {
    use HasFactory;
    protected $fillable = ['name', 'phone', 'email', 'address', 'total_due'];
    public function purchases() { return $this->hasMany(Purchase::class); }
    public function payments() { return $this->hasMany(PurchasePayment::class); }
}