<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount_value', 'discount_type', 'expiry_date', 'usage_limit', 'usage_count'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'redemption_code', 'code');
    }
}