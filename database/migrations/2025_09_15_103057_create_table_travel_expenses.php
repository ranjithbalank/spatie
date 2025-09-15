<?php

use App\Models\Designation;
use App\Models\User;
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
        // Schema::create('travel_expenses', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained("users");
        //     $table->foreignId("unit_id")->constrained("units");
        //     $table->foreignId("designation_id")->constrained("designations");
        //     $table->foreignId("department_id")->constrained("departments");
        //     $table->string("place_of_visit");
        //     $table->string("purpose_of_visit");

        //     // Travel Details
        //     $table->date("start_date");
        //     $table->date("end_date");
        //     $table->string("mode_of_travel");
        //     $table->integer("km_travelled")->nullable();
        //     $table->integer("pnr_number")->nullable();
        //     $table->integer("toll_cost")->nullable();
        //     $table->integer("total_travel_cost")->nullable();
        //     $table->enum('paid_by', ['company', 'self'])->default('self');
        //     $table->string("uploaded_travel_ticket_path")->nullable();

        //     // Accommodation Details
        //     $table->string("accommodation_type")->nullable();
        //     $table->string("hotel_name")->nullable();
        //     $table->integer("number_of_nights")->nullable();
        //     $table->integer("cost_per_night")->nullable();
        //     $table->integer("total_accommodation_cost")->nullable();
        //     $table->string("uploaded_accommodation_bill_path")->nullable();
        //     $table->string("enclosed_accommodation_bill")->nullable();

        //     // Local Conveyance
        //     $table->integer("local_conveyance_cost")->nullable();
        //     $table->string("uploaded_local_conveyance_bill_path")->nullable();
        //     $table->string("mode_of_local_conveyance")->nullable();

        //     // anyother expenses
        //     $table->string("any_other_expenses_description")->nullable();
        //     $table->integer("any_other_expenses_cost")->nullable();
        //     $table->string("uploaded_any_other_expenses_bill_path")->nullable();    
        //     $table->integer("total_expense_claimed")->nullable();



        //     $table->timestamps();
        // });
        Schema::create('travel_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('designation_id')->constrained('designations');
            $table->foreignId('department_id')->constrained('departments');

            // Travel Details
            $table->string('place_of_visit');
            $table->text('purpose_of_visit');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('mode_of_travel');
            $table->integer('km_travelled')->nullable();
            $table->string('pnr_number')->nullable();
            $table->decimal('toll_cost', 10, 2)->nullable();
            $table->decimal('total_travel_cost', 10, 2)->nullable();
            $table->enum('paid_by', ['company', 'self'])->default('self');
            $table->string('uploaded_travel_ticket_path')->nullable();

            // Accommodation Details
            $table->string('accommodation_type')->nullable();
            $table->string('hotel_name')->nullable();
            $table->integer('number_of_nights')->nullable();
            $table->decimal('cost_per_night', 10, 2)->nullable();
            $table->decimal('total_accommodation_cost', 10, 2)->nullable();
            $table->string('uploaded_accommodation_bill_path')->nullable();
            $table->boolean('enclosed_accommodation_bill')->nullable();

            // Local Conveyance
            $table->decimal('local_conveyance_cost', 10, 2)->nullable();
            $table->string('uploaded_local_conveyance_bill_path')->nullable();
            $table->string('mode_of_local_conveyance')->nullable();

            // Other Expenses
            $table->text('any_other_expenses_description')->nullable();
            $table->decimal('any_other_expenses_cost', 10, 2)->nullable();
            $table->string('uploaded_any_other_expenses_bill_path')->nullable();

            // Total
            $table->decimal('total_expense_claimed', 10, 2)->nullable();

            // Approval Workflow
            $table->boolean('employee_signed')->default(false);
            $table->enum('hod_approval_status', ['approved', 'referred_for_exception', 'pending'])->default('pending');
            $table->decimal('advance_paid', 10, 2)->nullable();
            $table->decimal('refund_received', 10, 2)->nullable();
            $table->enum('exception_approval_status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->foreignId('approved_by_hod')->nullable()->constrained('users');
            $table->foreignId('approved_by_cmd')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_expenses');
    }
};
