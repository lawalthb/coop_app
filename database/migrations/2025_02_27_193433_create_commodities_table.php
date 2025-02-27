<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commodities', function (Blueprint $table) {
             $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quantity_available')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
$table->dateTime('end_date')->nullable();
$table->string('image')->nullable();
$table->decimal('purchase_amount', 12, 2)->nullable();
$table->decimal('target_sales_amount', 12, 2)->nullable();
$table->decimal('profit_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
