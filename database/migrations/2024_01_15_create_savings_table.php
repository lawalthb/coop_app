<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('saving_type_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->foreignId('month_id')->constrained();
            $table->foreignId('year_id')->constrained();
            $table->string('reference')->unique();
            $table->string('status')->default('completed');
            $table->text('remark')->nullable();
            $table->foreignId('posted_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('savings');
    }
};
