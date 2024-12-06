<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getBalance(Request $request)
    {
        // ใช้ user ID จาก token หรือ mock user
       // $customer = Customer::findOrFail($request->user()->id);
       $customer = Customer::findOrFail(1);

        return response()->json([
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'balance' => $customer->balance
        ]);
    }


    
}