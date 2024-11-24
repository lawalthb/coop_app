<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('guarantor_name');
            $table->string('guarantor_phone');
            $table->text('guarantor_address');
            $table->string('guarantor_relationship')->nullable();
            $table->string('guarantor_member_no')->nullable();
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'guarantor_name',
                'guarantor_phone',
                'guarantor_address',
                'guarantor_relationship',
                'guarantor_member_no'
            ]);
        });
    }
};
