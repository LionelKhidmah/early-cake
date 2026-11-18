<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_cake_push_subscriptions', function (Blueprint $table) {
            $table->dropUnique(
                'custom_cake_push_subscriptions_endpoint_unique'
            );

            $table->string('endpoint_hash', 64)
                ->nullable()
                ->after('custom_cake_id');
        });

        DB::table('custom_cake_push_subscriptions')
            ->orderBy('id')
            ->get()
            ->each(function ($subscription) {
                DB::table('custom_cake_push_subscriptions')
                    ->where('id', $subscription->id)
                    ->update([
                        'endpoint_hash' => hash('sha256', $subscription->endpoint),
                    ]);
            });

        Schema::table('custom_cake_push_subscriptions', function (Blueprint $table) {
            $table->unique(
                ['custom_cake_id', 'endpoint_hash'],
                'custom_cake_push_subscriptions_cake_endpoint_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('custom_cake_push_subscriptions', function (Blueprint $table) {
            $table->dropUnique(
                'custom_cake_push_subscriptions_cake_endpoint_unique'
            );

            $table->dropColumn('endpoint_hash');

            $table->unique(
                'endpoint',
                'custom_cake_push_subscriptions_endpoint_unique'
            );
        });
    }
};
