<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'variants' => function ($query) {
                $query->whereHas('stocks', function ($stockQuery) {
                    $stockQuery->where('status_id', 1) // IN-STOCK
                               ->where('quantity', '>', 0); // มีสินค้าคงเหลือ
                });
            },
            'variants.color',
            'variants.capacity',
            'variants.images' // ดึงรูปภาพ
        ])->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with([
            'variants' => function ($query) {
                $query->whereHas('stocks', function ($stockQuery) {
                    $stockQuery->where('status_id', 1) // IN-STOCK
                               ->where('quantity', '>', 0); // มีสินค้าคงเหลือ
                });
            },
            'variants.color',
            'variants.capacity',
            'variants.images' // ดึงรูปภาพ
        ])->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
