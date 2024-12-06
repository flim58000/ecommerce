<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         $imagesByColor = [
            1 => [
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/blue-titanium/iphone-15-pro-max-pdp-image-position-1a-blue-titanium-color.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/blue-titanium/iphone-15-pro-max-pdp-image-position-2-design.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/blue-titanium/iphone-15-pro-max-pdp-image-position-4-camera.jpg',
            ],
            2 => [
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/natural-titanium/iphone-15-pro-max-pdp-image-position-1a-natural-titanium-color.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/natural-titanium/iphone-15-pro-max-pdp-image-position-2-design.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/natural-titanium/iphone-15-pro-max-pdp-image-position-4-camera.jpg',
            ],
            3 => [
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/white-titanium/iphone-15-pro-max-pdp-image-position-1a-white-titanium-color.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/white-titanium/iphone-15-pro-max-pdp-image-position-2-design.jpg',
                'https://www.ais.th/content/dam/ais/consumer/store/devices/apple/iphone/iphone-15-series/iphone-15-pro-max/product-detail/th/white-titanium/iphone-15-pro-max-pdp-image-position-4-camera.jpg',
            ],
        ];
    
        $images = [];
    
         $distinctVariants = DB::table('product_variants')
            ->select('id', 'color_id')
            ->groupBy('color_id') // ดึงเฉพาะรายการแรกของแต่ละ color_id
            ->pluck('id', 'color_id');
    
         foreach ($imagesByColor as $colorId => $urls) {
            // ตรวจสอบว่า color_id มีใน product_variants หรือไม่
            if (isset($distinctVariants[$colorId])) {
                $variantId = $distinctVariants[$colorId];
    
                foreach ($urls as $key => $url) {
                    $images[] = [
                        'variant_id' => $variantId,
                        'url' => $url,
                        'is_main' => $key === 0, // ภาพแรกเป็นภาพหลัก
                    ];
                }
            }
        }
    
         DB::table('product_images')->insert($images);
    }
}
