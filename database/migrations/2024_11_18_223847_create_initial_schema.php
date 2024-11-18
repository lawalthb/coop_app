<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create faculties table
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Create departments table
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 3. Create states table
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 4. Create lgas table
        Schema::create('lgas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 5. Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('title', ['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.']);
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername')->nullable();
            $table->text('home_address')->nullable();;
            $table->foreignId('department_id')->constrained();
            $table->foreignId('faculty_id')->constrained();
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->date('dob')->nullable();;
            $table->string('nationality')->nullable();;
            $table->foreignId('state_id')->constrained();
            $table->foreignId('lga_id')->constrained();
            $table->string('nok')->nullable();;
            $table->string('nok_relationship')->nullable();;
            $table->text('nok_address')->nullable();;
            $table->string('marital_status')->nullable();;
            $table->string('religion')->nullable();;
            $table->string('nok_phone')->nullable();;
            $table->decimal('monthly_savings', 10, 2)->nullable();;
            $table->decimal('share_subscription', 10, 2)->nullable();;
            $table->string('month_commence')->nullable();
            $table->string('staff_no')->unique()->nullable();
            $table->string('signature_image')->nullable()->nullable();
            $table->date('date_join')->nullable();
            $table->text('admin_remark')->nullable();
            $table->enum('admin_sign', ['Yes', 'No'])->default('No');
            $table->timestamp('admin_signdate')->nullable();
            $table->string('member_no')->unique();
            $table->string('gensec_sign_image')->nullable();
            $table->string('president_sign')->nullable();
            $table->string('member_image')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_approved')->default(false);
          
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop tables in reverse order
        Schema::dropIfExists('users');
        Schema::dropIfExists('lgas');
        Schema::dropIfExists('states');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
    }
};
