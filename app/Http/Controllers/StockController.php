<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;

class StockController extends Controller
{
    // เช็คสต็อกของสินค้าเดี่ยว
    public function checkStock(Request $request)
    {

        try{
        $validated = $request->validate([
            'id' => 'required|integer',
            'requestedQuantity' => 'required|integer',
        ]);
        
        $variant = ProductVariant::with('stocks') // ดึง stocks ที่เกี่ยวข้อง
            ->find($validated['id']);
        
        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        // คำนวณ stock เฉพาะที่สถานะ 'in stock'
        $totalStock = $variant->stocks
            ->where('status_id', 1) // 1 คือสถานะ 'in stock'
            ->sum('quantity');
        
        if ($totalStock >= $validated['requestedQuantity']) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Not enough stock']);
        }
    }catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 400);
    }
        
    }

    // เช็คสต็อกสำหรับสินค้าในตะกร้าทั้งหมด
    public function checkCartStock(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|array',
        ]);
        
        $cart = $validated['cart'];
        $results = [];
        
        foreach ($cart as $item) {
            $variant = ProductVariant::with('stocks')->find($item['id']);
        
            if (!$variant) {
                $results[] = [
                    'id' => $item['id'],
                    'status' => 'not_found',
                    'message' => 'Product not found',
                ];
                continue;
            }
        
            // รวมจำนวน stock เฉพาะที่สถานะ in stock
            $totalStock = $variant->stocks
                ->where('status_id', 1) // 1 คือสถานะ 'in stock'
                ->sum('quantity');
        
            if ($totalStock < $item['quantity']) {
                $results[] = [
                    'id' => $item['id'],
                    'status' => 'out_of_stock',
                    'message' => 'Out of stock',
                ];
            } else {
                $results[] = [
                    'id' => $item['id'],
                    'status' => 'available',
                ];
            }
        }
        
        return response()->json($results);
    }
}
