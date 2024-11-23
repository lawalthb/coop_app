<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('loan_type_id')->constrained();
    $table->string('reference')->unique();
    $table->decimal('amount', 15, 2);
    $table->decimal('interest_amount', 15, 2);
    $table->decimal('total_amount', 15, 2);
    $table->integer('duration');
    $table->decimal('monthly_payment', 15, 2);
    $table->date('start_date');
    $table->date('end_date');
    $table->string('status')->default('pending');
    $table->text('purpose')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->foreignId('posted_by')->constrained('users');
    $table->timestamps();
});
    }
};
