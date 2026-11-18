<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_push_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('custom_cake_id')
                ->constrained('custom_cakes')
                ->cascadeOnDelete();

            $table->text('endpoint')->unique();
            $table->text('public_key');
            $table->text('auth_token');
            $table->string('content_encoding')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_push_subscriptions');
    }
};
