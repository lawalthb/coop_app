<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('share_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_per_unit', 10, 2);
            $table->integer('minimum_units')->default(1);
            $table->integer('maximum_units')->nullable();
            $table->decimal('dividend_rate', 5, 2)->default(0);
            $table->boolean('is_transferable')->default(true);
            $table->boolean('has_voting_rights')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_types');
    }
};
