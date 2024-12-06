<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;  
 
class ProductSeeder extends Seeder
{
    public function run()
    {
        // Product::factory(1)->create()->each(function ($product) {
        //     $variants = ProductVariant::factory(3)->create(['product_id' => $product->id]);
            
        //     $variants->each(function ($variant) {
        //         // เพิ่ม Stock สำหรับแต่ละสถานะ (IN-STOCK เท่านั้นในตอนแรก)
        //         Stock::create([
        //             'variant_id' => $variant->id,
        //             'status_id' => 1,  
        //             'quantity' => 10,  
        //         ]);
        //     });
        // });


        Product::factory(1)->create()->each(function ($product) {
           
            $colors = DB::table('colors')->pluck('id')->take(3);
        
             $capacities = DB::table('capacities')->pluck('id')->take(3);  
        
             foreach ($colors as $colorId) {
                foreach ($capacities as $capacityId) {
                    // สร้าง Variant
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $colorId,
                        'capacity_id' => $capacityId,
                        'price' => 10000,  
                        'sku' => "SKU-{$product->id}-{$colorId}-{$capacityId}",
                    ]);
        
                    // เพิ่ม Stock
                    Stock::create([
                        'variant_id' => $variant->id,
                        'status_id' => 1, // IN-STOCK
                        'quantity' => 10, // จำนวนเริ่มต้น
                    ]);
                }
            }
        });


    }
}