<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();

            // Informasi promo
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('badge')->nullable();
            $table->text('description');
            $table->string('image')->nullable();

            // Pengaturan diskon
            $table->string('code')->unique();
            $table->enum('discount_type', [
                'percentage',
                'fixed',
            ]);
            $table->decimal('discount_value', 12, 2);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->decimal('min_purchase', 12, 2)->default(0);

            // Penggunaan promo
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('first_order_only')->default(false);

            // Periode dan status
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_active')->default(true);

            // Syarat dan ketentuan
            $table->json('terms')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
