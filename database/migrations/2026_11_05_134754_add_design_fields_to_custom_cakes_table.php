<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_cakes', function (Blueprint $table) {
            $table->string('shape')->nullable()->after('cake_type');
            $table->string('color')->nullable()->after('theme');
            $table->json('decorations')->nullable()->after('color');
            $table->json('design_config')->nullable()->after('decorations');
        });
    }

    public function down(): void
    {
        Schema::table('custom_cakes', function (Blueprint $table) {
            $table->dropColumn([
                'shape',
                'color',
                'decorations',
                'design_config',
            ]);
        });
    }
};
