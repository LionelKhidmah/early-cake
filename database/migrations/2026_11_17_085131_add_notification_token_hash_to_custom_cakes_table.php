<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_cakes', function (Blueprint $table) {
            $table->string('notification_token_hash', 64)
                ->nullable()
                ->unique()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('custom_cakes', function (Blueprint $table) {
            $table->dropUnique(['notification_token_hash']);
            $table->dropColumn('notification_token_hash');
        });
    }
};
