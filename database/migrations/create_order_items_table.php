<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('quantity')->default(1);

            $table->decimal('base_price', 15, 2);
            $table->decimal('discount_percent', 5, 2)->default(0.00);
            $table->decimal('commission_percent', 5, 2)->default(0.00);
            $table->decimal('jump_percent', 5, 2)->default(0.00);

            $table->decimal('final_price', 15, 2);

            $table->index('order_id', 'idx_order_items_order');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
