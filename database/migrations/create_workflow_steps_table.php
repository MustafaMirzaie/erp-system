<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('step_order');
            $table->unsignedBigInteger('role_id');

            $table->index('role_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
