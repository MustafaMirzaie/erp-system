<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('step_id');
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'revision'
            ])->default('pending');
            $table->unsignedBigInteger('action_by')->nullable();
            $table->dateTime('action_at')->nullable();

            $table->text('description')->nullable();

            // indexes مشابه SQL dump
            $table->index('order_id', 'idx_approvals_order');
            $table->index('step_id');
            $table->index('action_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_approvals');
    }
};
