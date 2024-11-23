<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saving_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('minimum_balance', 10, 2)->default(0);
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('allow_withdrawal')->default(true);
            $table->integer('withdrawal_restriction_days')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saving_types');
    }
};
