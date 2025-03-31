<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->boolean('allow_installment')->default(false)->after('is_active');
            $table->integer('max_installment_months')->nullable()->after('allow_installment');
            $table->decimal('monthly_installment_amount', 12, 2)->nullable()->after('max_installment_months');
            $table->decimal('initial_deposit_percentage', 5, 2)->default(0)->after('monthly_installment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropColumn([
                'allow_installment',
                'max_installment_months',
                'monthly_installment_amount',
                'initial_deposit_percentage'
            ]);
        });
    }
};
