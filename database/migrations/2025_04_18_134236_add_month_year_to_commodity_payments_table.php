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
        Schema::table('commodity_payments', function (Blueprint $table) {
            $table->foreignId('month_id')->nullable()->constrained();
            $table->foreignId('year_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commodity_payments', function (Blueprint $table) {
            $table->dropForeign(['month_id']);
            $table->dropForeign(['year_id']);
            $table->dropColumn(['month_id', 'year_id']);
        });
    }
};
