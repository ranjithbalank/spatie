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
        // Schema::create('leaves', function (Blueprint $table) {

        //     $table->id();
        //     $table->foreignId('emp_id')->constrained('employees_details')->onDelete('cascade'); // Employee
        //     $table->date('start_date');
        //     $table->date('end_date');
        //     $table->integer('total_days');
        //     $table->text('reason')->nullable();

        //     // Stage 1: Manager Approval
        //     $table->enum('manager_status', ['pending', 'approved', 'rejected'])->default('pending');
        //     $table->foreignId('manager_id')->nullable()->constrained('employees_details')->nullOnDelete();
        //     $table->timestamp('manager_action_at')->nullable();
        //     $table->text('manager_remark')->nullable();

        //     // Stage 2: HR Approval (role-based, no hr_id)
        //     $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
        //     $table->timestamp('hr_action_at')->nullable();
        //     $table->text('hr_remark')->nullable();

        //     // HR Decides Leave Type
        //     $table->enum('leave_type', ['sick', 'casual', 'paid', 'unpaid', 'maternity', 'other'])->nullable();

        //     $table->timestamps();
        // });
           Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('leave_type', ["N/A",'casual', 'sick', 'earned', 'comp-off', 'od', 'permission'])
                ->default('N/A')->nullable();
            $table->enum('leave_duration', ['N/A','Full Day', 'Half Day'])->nullable();

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->date('comp_off_worked_date')->nullable();
            $table->date('comp_off_leave_date')->nullable();

            $table->decimal('leave_days', 4, 2);
            $table->text('reason')->nullable();

            // Approver details & comments
            $table->text('approver_1')->nullable(); // usually manager name or ID
            $table->text('approver_2')->nullable(); // usually HR/Admin name or ID

            $table->timestamp('approver_1_approved_at')->nullable();
            $table->timestamp('approver_2_approved_at')->nullable();

            $table->text('approver_1_comments')->nullable();
            $table->text('approver_2_comments')->nullable();

            // Single status column to track workflow
            $table->enum('status', [
                'pending',                      // waiting for manager
                'supervisor/ manager approved', // manager approved, waiting for HR
                'supervisor/ manager rejected',
                'hr approved',
                'hr rejected',
            ])->default('pending');

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
