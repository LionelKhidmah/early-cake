<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_options', function (Blueprint $table) {
            $table->id();

            $table->string('option_type', 30);
            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->decimal('additional_price', 12, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['option_type', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_options');
    }
};
