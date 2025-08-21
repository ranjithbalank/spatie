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
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("unit_id")->constrained("units");
            $table->foreignId("department_id")->constrained("departments");
            $table->foreignId("designation_id")->constrained("designations");
            $table->unsignedBigInteger("emp_id")->unique();
            $table->string('employee_name');
            $table->date("doj");
            $table->date("dor")->nullable();
            $table->unsignedBigInteger("manager_id")->nullable(); // Made nullable
            $table->float("leave_balance")->nullable();
            $table->datetime("last_login_at")->nullable();
            $table->datetime("last_logout_at")->nullable();
            $table->string("status");
            $table->string("created_by");
            $table->string("updated_by");
            $table->timestamps();
        });

        // Add the foreign key constraint AFTER the table is created
        Schema::table('employees_details', function (Blueprint $table) {
            $table->foreign("manager_id")
                  ->references("emp_id")
                  ->on("employees_details")
                  ->onDelete('set null'); // Added onDelete for better data integrity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_details', function (Blueprint $table) {
            $table->dropForeign(['manager_id']); // Drop the foreign key first
        });

        Schema::dropIfExists('employees_details');
    }
};
