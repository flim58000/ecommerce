<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['variant_id', 'url', 'is_main'];

     

    public function variant()
{
    return $this->belongsTo(ProductVariant::class, 'variant_id');
}
}
