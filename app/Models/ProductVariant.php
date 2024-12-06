<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'color_id', 'capacity_id', 'price', 'sku'];

     public function product()
    {
        return $this->belongsTo(Product::class);
    }

     public function color()
    {
        return $this->belongsTo(Color::class);
    }

     public function capacity()
    {
        return $this->belongsTo(Capacity::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'variant_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id');  
    }
}
