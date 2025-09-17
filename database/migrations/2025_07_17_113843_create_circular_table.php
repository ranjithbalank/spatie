<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ------------------------------------------------------------
 * Migration: Create Circulars Table
 * ------------------------------------------------------------
 * Table Name   : circulars
 * Description  : Stores official circulars/announcements 
 *                released within the organization.
 * Fields       :
 *   - id              : Primary key
 *   - circular_no     : Unique circular number/identifier
 *   - circular_name   : Title or subject of the circular
 *   - circular_date   : Date of issue
 *   - created_by      : Name or ID of the creator
 *   - file_path       : File storage path (e.g., PDF, DOC)
 *   - timestamps      : created_at & updated_at
 *
 * Version Ctrl : 
 *   - Commit this migration with a clear message:
 *       "migration: create circulars table"
 *   - If schema changes later, create a new migration 
 *     (do not edit this one) to preserve version history.
 *
 * Author       : Ranjithbalan K / Saran Karthick 
 * Laravel Ver. : 12.x
 * ------------------------------------------------------------
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the 'circulars' table with fields for
     * circular metadata and uploaded file path.
     */
    public function up(): void
    {
        Schema::create('circulars', function (Blueprint $table) {
            $table->id();                               // Primary key (auto-increment)
            $table->string("circular_no")->unique();    // Unique circular reference number
            $table->string("circular_name")->unique();  // Unique circular title/subject
            $table->date("circular_date");              // Date of circular issue
            $table->string("created_by");               // Created by (user or system)
            $table->string("file_path");                // File path for uploaded circular
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'circulars' table.
     */
    public function down(): void
    {
        Schema::dropIfExists('circulars'); // ✅ fixed typo (was 'circular')
    }
};
