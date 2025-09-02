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
    Schema::table('leaves', function (Blueprint $table) {
        // Change approver_1 to string
        $table->string('approver_1')->nullable()->change();
        $table->string('approver_2')->nullable()->change();

        // Add the foreign key constraints
        $table->foreign('approver_1')
              ->references('emp_id')
              ->on('employees_details')
              ->nullOnDelete();

        $table->foreign('approver_2')
              ->references('emp_id')
              ->on('employees_details')
              ->nullOnDelete();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foreign_keys', function (Blueprint $table) {
            //
        });
    }
};
