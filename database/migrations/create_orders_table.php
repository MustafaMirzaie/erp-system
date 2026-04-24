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

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('contact_id');

            $table->boolean('is_official')->default(1);
            $table->decimal('total_price', 15, 2)->nullable();

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'revision'
            ])->default('draft');

            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->useCurrent();

            // Indexes مطابق dump
            $table->index('customer_id', 'idx_orders_customer');
            $table->index('status', 'idx_orders_status');
            $table->index('company_id');
            $table->index('address_id');
            $table->index('contact_id');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
