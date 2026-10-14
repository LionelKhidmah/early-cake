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
        Schema::create('custom_cakes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('cake_type');
            $table->string('flavor');
            $table->string('size')->nullable();
            $table->string('theme')->nullable();
            $table->string('message')->nullable();
            $table->string('reference_image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('estimated_price', 12, 2)->nullable();
            $table->decimal('final_price', 12, 2)->nullable();

            $table->enum('status', [
                'pending',
                'reviewed',
                'approved',
                'rejected',
                'completed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_cakes');
    }
};
