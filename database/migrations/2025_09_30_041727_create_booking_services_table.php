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
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('opening_stock');
            $table->integer('stock_in');
            $table->integer('stock_out');
            $table->integer('closing_stock');
            $table->decimal('stock_value', 15, 2);
            $table->decimal('daily_usage', 8, 2);
            $table->timestamps();
            $table->unique(['snapshot_date', 'product_id']);
            $table->index(['product_id', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
