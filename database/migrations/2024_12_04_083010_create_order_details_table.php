<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete(); 
            $table->foreignId('product_id')->constrained('product_variants')->cascadeOnDelete();  
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // ราคาสินค้าต่อหน่วย
            $table->decimal('total_price', 10, 2); // ราคารวม (quantity * price)
            $table->timestamps();

            // เพิ่ม Index
            $table->index('order_id', 'order_id_index'); // Index สำหรับ order_id
         });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // ลบ Index
            $table->dropIndex('order_id_index');
         });

        Schema::dropIfExists('order_details');
    }
};
