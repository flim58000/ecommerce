<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'status', 'total_amount', 'discount_applied', 'final_amount', 'redemption_code'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function redemptionCode()
{
    return $this->belongsTo(RedemptionCode::class, 'redemption_code', 'code');
}
}
