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
        Schema::create('employees_details', function (Blueprint $table) {
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
            $table->string('manager_id')->nullable();

            // FIX: designation_id must be an unsignedBigInteger to match the 'id' column on the designations table
            $table->unsignedBigInteger('designation_id')->nullable();

            $table->enum('status', ['active',"inactive"])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // All foreign key constraints can be defined in a separate schema operation
        Schema::table('employees_details', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('department_id')->references('id')->on('departments');

            // This is the new corrected foreign key
            $table->foreign('designation_id')->references('id')->on('designations');

            $table->foreign('manager_id')->references('emp_id')->on('employees_details');
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
