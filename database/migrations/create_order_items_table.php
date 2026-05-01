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
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('packaging_type_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('base_price', 15, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('jump_percent', 5, 2)->default(0);
            $table->decimal('final_price', 15, 2);
            $table->decimal('tax_percent', 5, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
