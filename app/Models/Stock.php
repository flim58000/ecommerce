<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = ['variant_id', 'status_id', 'quantity'];

     public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

     public function status()
    {
        return $this->belongsTo(StockStatus::class);
    }
}
