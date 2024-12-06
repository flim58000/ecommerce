<?php

namespace Database\Factories;

use App\Models\RedemptionCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedemptionCodeFactory extends Factory
{
    protected $model = RedemptionCode::class;

    public function definition()
    {
        return [
            'code' => 'DISCOUNT10', // Fix ไว้ที่ DISCOUNT10
            'discount_type' => 'percent', // ประเภทส่วนลด (percent หรือ flat)
            'discount_value' => 10, // 10%
            'expiry_date' => now()->addDays(30), // หมดอายุในอีก 30 วัน
            'usage_limit' => 3, // ใช้ได้สูงสุด 100 ครั้ง
            'usage_count' => 0, // เริ่มต้นที่ 0 ครั้ง
        ];
    }
}