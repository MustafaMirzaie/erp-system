<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            $table->index('address_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_contacts');
    }
};
