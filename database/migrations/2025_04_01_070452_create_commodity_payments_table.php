<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commodity_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('commodity_subscriptions')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_type'); // 'full', 'initial_deposit', or 'installment'
            $table->date('payment_date');
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commodity_payments');
    }
};
