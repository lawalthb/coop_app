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
            $table->decimal('interest_rate', 5, 2);
            $table->integer('max_duration');
            $table->decimal('minimum_amount', 15, 2);
            $table->decimal('maximum_amount', 15, 2);
            $table->enum('saved_percentage', ['50', '100', '150', '200', '250', '300', 'None'])->default('none');
            $table->integer('no_guarantors')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
};
