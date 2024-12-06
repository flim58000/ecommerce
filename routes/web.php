<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockController;


Route::get('/api/documentation', function () {
    return view('l5-swagger::index');
});

Route::get('api/products', [ProductController::class, 'index']); // ดึงสินค้าทั้งหมด
Route::get('api/products/{id}', [ProductController::class, 'show']); // ดึงสินค้าตาม ID


Route::post('api/orders', [OrderController::class, 'store']);  
Route::get('api/orders/{orderId}', [OrderController::class, 'show']);  
Route::get('api/orders/', [OrderController::class, 'index']);  
Route::get('/api/codes', [OrderController::class, 'getRedemptionCodes']);
Route::post('api/applydiscount', [OrderController::class, 'applydiscount']);  

Route::post('/api/checkstock', [StockController::class, 'checkStock']);
Route::post('/api/checkcartstock', [StockController::class, 'checkCartStock']);

Route::get('/api/balance', [CustomerController::class, 'getBalance']);
