<?php

    namespace App\Http\Controllers;

    use App\Models\Customer;
    use App\Models\Order;
    use App\Models\OrderDetail;
    use App\Models\Stock;
    use App\Models\RedemptionCode;
    use App\Models\ProductVariant;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    class OrderController extends Controller
    {
        public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'order_details' => 'required|array',
        'order_details.*.product_id' => 'required|exists:product_variants,id',
        'order_details.*.quantity' => 'required|integer|min:1',
        'redemption_code' => 'nullable|string|exists:redemption_codes,code',
    ]);

    DB::beginTransaction();

    try {
        $customer = Customer::findOrFail($validated['customer_id']);
        $totalAmount = 0;
        $orderDetailsData = [];

        foreach ($validated['order_details'] as $detail) {
            $productId = $detail['product_id'];
            $quantity = $detail['quantity'];

            // ตรวจสอบ IN-STOCK
            $stock = Stock::where('variant_id', $productId)
                ->where('status_id', 1) // IN-STOCK
                ->lockForUpdate()
                ->first();

            if (!$stock || $stock->quantity < $quantity) {
                throw new \Exception('Insufficient stock for product ID ' . $productId);
            }

            // ลดจำนวนสินค้าใน IN-STOCK
            $stock->decrement('quantity', $quantity);

            // เพิ่มสินค้าใน RESERVED
            $reservedStock = Stock::firstOrNew(
                ['variant_id' => $productId, 'status_id' => 2] // RESERVED
            );
            $reservedStock->quantity = ($reservedStock->quantity ?? 0) + $quantity;
            $reservedStock->save();

            $productVariant = ProductVariant::findOrFail($productId);
            $price = $productVariant->price;

            $totalPrice = $quantity * $price;
            $totalAmount += $totalPrice;

            if (!$productId  ) {
                throw new \Exception(' No product ID ' . $productId);
            }



            $orderDetailsData[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
            ];
        }

        // ตรวจสอบโค้ดส่วนลด
        $discountApplied = 0;
        $redemptionCode = null;

        if (!empty($validated['redemption_code'])) {
            $code = RedemptionCode::where('code', $validated['redemption_code'])
                ->lockForUpdate()
                ->first();

            if (!$code || $code->expiry_date < now() || ($code->usage_limit !== null && $code->usage_count >= $code->usage_limit)) {
                throw new \Exception('Invalid or expired redemption code.');
            }

            $discountApplied = $code->discount_type === 'percent'
                ? ($code->discount_value / 100) * $totalAmount
                : $code->discount_value;

            $discountApplied = min($discountApplied, $totalAmount);
            $code->increment('usage_count');
            $redemptionCode = $code->code;
        }

        $finalAmount = $totalAmount - $discountApplied;

        // ตรวจสอบยอดเงินลูกค้า
        if ($customer->balance < $finalAmount) {
            DB::rollBack();
            return response()->json([
                'error' => 'Insufficient balance.',
                'required_balance' => $finalAmount,
                'current_balance' => $customer->balance,
            ], 400);
        }

        // หักยอดเงินลูกค้า
        $customer->decrement('balance', $finalAmount);

        // สร้างคำสั่งซื้อ
        $order = Order::create([
            'customer_id' => $customer->id,
            'status' => 'PROCESSING',
            'total_amount' => $totalAmount,
            'discount_applied' => $discountApplied,
            'final_amount' => $finalAmount,
            'redemption_code' => $redemptionCode,
        ]);

        foreach ($orderDetailsData as $detailData) {
            $detailData['order_id'] = $order->id;
            OrderDetail::create($detailData);

            // ย้าย RESERVED ไป SOLD
            $reservedStock = Stock::where([
                'variant_id' => $detailData['product_id'],
                'status_id' => 2,
            ])->lockForUpdate()->first();

            $reservedStock->decrement('quantity', $detailData['quantity']);

            $soldStock = Stock::firstOrNew(
                ['variant_id' => $detailData['product_id'], 'status_id' => 3] // SOLD
            );
            $soldStock->quantity = ($soldStock->quantity ?? 0) + $detailData['quantity'];
            $soldStock->save();
        }

        DB::commit();

        return response()->json([
            'transaction_id' => $order->id,
            'status' => 'PROCESSING',
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}





public function getRedemptionCodes(Request $request)
{
    // ใช้ user ID จาก token หรือ mock user
   // $customer = Customer::findOrFail($request->user()->id);
   $customerId = $request->header('X-Customer-ID');

    // Query Redemption Codes ของลูกค้าคนนี้
   

    $codes = Order::with('redemptionCode:id,code,discount_type,discount_value')
        ->where('customer_id', $customerId)
        ->whereNotNull('redemption_code') // เฉพาะคำสั่งซื้อที่มี redemption_code
        ->get(['id as order_id', 'redemption_code']);

    return response()->json($codes);
}


public function index(Request $request)
{
    // ดึงค่า customer_id จาก Header
    $customerId = $request->header('X-Customer-ID');

    if (!$customerId) {
        return response()->json(['error' => 'Customer ID is required'], 400);
    }

    // ตรวจสอบว่ามี Customer นี้จริงหรือไม่
    $customer = Customer::find($customerId);

    if (!$customer) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // ดึงข้อมูล Order ของลูกค้า
    $orders = Order::where('customer_id', $customerId)
        ->with(['details.variant.product', 'details.variant.images'])
        ->get();

    return response()->json($orders);
}




public function applydiscount(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|string',
    ]);

    // ค้นหาโค้ดในฐานข้อมูล
    $discount = RedemptionCode::where('code', $validated['code'])->first();

    if (!$discount) {
        return response()->json(['success' => false, 'message' => 'Invalid discount code'], 400);
    }

    // ตรวจสอบว่าโค้ดหมดอายุหรือไม่
    if ($discount->expiry_date && $discount->expiry_date < now()) {
        return response()->json([
            'success' => false,
            'message' => 'This discount code has expired',
        ], 400);
    }

    // ตรวจสอบจำนวนการใช้งาน
    if ($discount->usage_limit && $discount->usage_count >= $discount->usage_limit) {
        return response()->json([
            'success' => false,
            'message' => 'This discount code has reached its usage limit',
        ], 400);
    }

    // คืนค่าหากผ่านการตรวจสอบทั้งหมด
    return response()->json([
        'success' => true,
        'discount_value' => $discount->discount_value,
        'discount_type' => $discount->discount_type,
        'message' => 'Discount code applied successfully',
    ]);
}


    
        public function show($orderId)
        {
            $order = Order::with(['details', 'customer'])->find($orderId);
    
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
    
            return response()->json([
                'order_id' => $order->id,
                'status' => $order->status,
                'customer' => [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'email' => $order->customer->email,
                ],
                'redemption_code' => $order->redemption_code,
                'total_amount' => $order->total_amount,
                'discount_applied' => $order->discount_applied,
                'final_amount' => $order->final_amount,
                'details' => $order->details,
            ]);
        }
    }

