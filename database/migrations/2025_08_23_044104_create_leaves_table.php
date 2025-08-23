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
        Schema::create('leaves', function (Blueprint $table) {

            $table->id();
            $table->foreignId('emp_id')->constrained('employees_details')->onDelete('cascade'); // Employee
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();

            // Stage 1: Manager Approval
            $table->enum('manager_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('manager_id')->nullable()->constrained('employees_details')->nullOnDelete();
            $table->timestamp('manager_action_at')->nullable();
            $table->text('manager_remark')->nullable();

            // Stage 2: HR Approval (role-based, no hr_id)
            $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('hr_action_at')->nullable();
            $table->text('hr_remark')->nullable();

            // HR Decides Leave Type
            $table->enum('leave_type', ['sick', 'casual', 'paid', 'unpaid', 'maternity', 'other'])->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
