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
            $table->string('emp_id');
            $table->string('emp_name');
            $table->string('unit_id');
            $table->string("department_id");
            $table->date('dob');
            $table->date('doj');
            $table->string('shift_type');
            $table->string('manager_id');
            $table->string('designation');


            $table->timestamps();
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
