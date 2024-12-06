<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
{
    return $this->hasMany(ProductImage::class, 'product_id');
}
}

