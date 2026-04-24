<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearance_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('school_year', 20);

            // Clearance Components
            $table->string('finance_status', 50)->default('pending');    // cleared, pending, overdue
            $table->string('library_status', 50)->default('pending');    // cleared, pending
            $table->string('records_status', 50)->default('pending');    // cleared, pending
            $table->string('academic_standing', 50)->default('pending'); // passed, failed, pending

            // Overall Status (auto-calculated)
            $table->string('overall_status', 50)->default('pending');    // cleared, pending, overdue

            // Verification — who cleared each component
            $table->string('finance_cleared_by', 255)->nullable();
            $table->timestamp('finance_cleared_at')->nullable();
            $table->string('library_cleared_by', 255)->nullable();
            $table->timestamp('library_cleared_at')->nullable();
            $table->string('records_cleared_by', 255)->nullable();
            $table->timestamp('records_cleared_at')->nullable();
            $table->string('academic_cleared_by', 255)->nullable();
            $table->timestamp('academic_cleared_at')->nullable();

            // Remarks per component
            $table->text('finance_remarks')->nullable();
            $table->text('library_remarks')->nullable();
            $table->text('records_remarks')->nullable();
            $table->text('academic_remarks')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('enrollment_id')->references('id')->on('student_enrollment')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // One clearance record per enrollment
            $table->unique('enrollment_id', 'unique_enrollment_clearance');

            // Indexes
            $table->index(['student_id', 'school_year']);
            $table->index('overall_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_table');
    }
};