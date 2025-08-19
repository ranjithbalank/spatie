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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('emp_id')->unique();
            $table->string('emp_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('department_id');
            $table->date('dob');
            $table->date('doj');
            $table->date('dor')->nullable();
            $table->enum('shift_type', ["general","shift"])->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('designation');
            $table->enum('status', ['active',"inactive"])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // Add foreign key constraints in a separate schema operation
        Schema::table('employee_details', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('manager_id')->references('id')->on('employee_details');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_details');
    }
};
