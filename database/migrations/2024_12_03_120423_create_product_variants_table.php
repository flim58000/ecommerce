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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->cascadeOnDelete();
            $table->foreignId('capacity_id')->nullable()->constrained('capacities')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('sku')->unique();
            $table->timestamps();

            // Composite Unique Constraint
            $table->unique(['product_id', 'color_id', 'capacity_id'], 'product_color_capacity_unique');

             $table->index(['product_id', 'color_id'], 'product_color_index');

             $table->index('capacity_id', 'capacity_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
             $table->dropIndex('product_color_index');
            $table->dropIndex('capacity_index');

             $table->dropUnique('product_color_capacity_unique');
        });

        Schema::dropIfExists('product_variants');
    }
};
