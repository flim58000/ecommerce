<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redemption_codes', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('code')->unique(); // ชื่อโค้ด เช่น DISCOUNT10
            $table->decimal('discount_value', 10, 2); // มูลค่าส่วนลด
            $table->enum('discount_type', ['flat', 'percent']); // ประเภทส่วนลด
            $table->dateTime('expiry_date'); // วันหมดอายุ
            $table->integer('usage_limit')->default(1); // จำนวนครั้งที่ใช้งานได้ทั้งหมด
            $table->integer('usage_count')->default(0); // จำนวนครั้งที่ถูกใช้งานแล้ว
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redemption_codes');
    }
};
