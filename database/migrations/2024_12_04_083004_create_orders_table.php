<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    

        Schema::create('orders', function (Blueprint $table) {

            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('status', ['PROCESSING', 'COMPLETED', 'FAILED']);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('redemption_code')->nullable();
            $table->timestamps();

            $table->index('customer_id', 'customer_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('customer_index'); // ลบ Index customer_id
        });

        Schema::dropIfExists('orders');
    }
};
