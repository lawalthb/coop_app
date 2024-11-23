<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('number_of_shares');
            $table->decimal('amount_per_share', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('certificate_number')->unique();
            $table->string('status')->default('active');
            $table->foreignId('posted_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('share_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('transaction_type'); // purchase, transfer
            $table->integer('number_of_shares');
            $table->decimal('amount', 15, 2);
            $table->string('reference')->unique();
            $table->string('status')->default('completed');
            $table->text('remark')->nullable();
            $table->foreignId('posted_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_transactions');
        Schema::dropIfExists('shares');
    }
};
