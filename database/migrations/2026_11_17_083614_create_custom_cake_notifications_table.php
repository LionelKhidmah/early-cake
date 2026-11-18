<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('custom_cake_id')
                ->constrained('custom_cakes')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('message');
            $table->string('type')->default('status');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['custom_cake_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_notifications');
    }
};
