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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit', 20)->default('pcs');
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(5);
            $table->integer('max_stock')->default(100);
            $table->integer('reorder_point')->default(10);
            $table->integer('reorder_quantity')->default(20);
            $table->decimal('avg_daily_usage', 8, 2)->default(0);
            $table->integer('lead_time_days')->default(3);
            $table->string('brand')->nullable();
            $table->string('compatible_vehicles')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['sku', 'name']);
            $table->index('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
