<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained('stock_statuses')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();

            // Unique Constraint สำหรับ variant_id และ status_id
            $table->unique(['variant_id', 'status_id'], 'variant_status_unique');

            // Index สำหรับ status_id
            $table->index('status_id', 'status_index');

            // Index สำหรับ variant_id
            $table->index('variant_id', 'variant_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
             $table->dropIndex('status_index');
            $table->dropIndex('variant_index');

             $table->dropUnique('variant_status_unique');
        });

        Schema::dropIfExists('stocks');
    }
};
