<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commodity_subscriptions', function (Blueprint $table) {
            $table->string('payment_type')->default('full')->after('reference'); // 'full' or 'installment'
            $table->decimal('initial_deposit', 12, 2)->nullable()->after('payment_type');
            $table->decimal('monthly_amount', 12, 2)->nullable()->after('initial_deposit');
            $table->integer('installment_months')->nullable()->after('monthly_amount');
            $table->integer('months_paid')->default(0)->after('installment_months');
            $table->date('next_payment_date')->nullable()->after('months_paid');
            $table->boolean('is_completed')->default(false)->after('next_payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('commodity_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'initial_deposit',
                'monthly_amount',
                'installment_months',
                'months_paid',
                'next_payment_date',
                'is_completed'
            ]);
        });
    }
};
