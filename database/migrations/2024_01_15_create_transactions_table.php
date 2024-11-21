<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // savings, loan, withdrawal
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('reference')->unique();
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
