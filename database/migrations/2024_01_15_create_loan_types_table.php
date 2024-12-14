<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('required_active_savings_months')->default(6);
            $table->decimal('savings_multiplier', 5, 2)->default(2.00);

            // Interest rates for different durations
            $table->decimal('interest_rate_12_months', 5, 2)->default(10.00);
            $table->decimal('interest_rate_18_months', 5, 2)->default(15.00);

            // Maximum duration in months
            $table->integer('max_duration_months')->default(18);

            $table->decimal('minimum_amount', 15, 2);
            $table->decimal('maximum_amount', 15, 2);
            $table->boolean('allow_early_payment')->default(true);

            // Additional fields from original migration
            $table->enum('saved_percentage', ['50', '100', '150', '200', '250', '300', 'None'])->default('none');
            $table->integer('no_guarantors')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
};
