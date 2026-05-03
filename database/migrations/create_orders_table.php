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
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('address_id')->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->boolean('is_official')->default(true);
            $table->date('issue_date')->nullable();
            $table->date('send_date')->nullable();
            $table->unsignedBigInteger('freight_type_id')->nullable();
            $table->decimal('freight_amount', 15, 2)->default(0);
            $table->decimal('insurance_amount', 15, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'revision'])->default('draft');
            $table->unsignedBigInteger('created_by')->index();
            $table->enum('payment_type', ['cash', 'check', 'credit'])->default('cash');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
