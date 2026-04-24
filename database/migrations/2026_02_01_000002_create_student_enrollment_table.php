<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrollment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->string('school_year', 20);

            // Academic
            $table->string('grade_level', 50)->nullable();
            $table->string('grade_level_applied', 50)->nullable();
            $table->string('program_level', 50)->nullable()->comment('Elementary, Junior High School, Senior High School');
            $table->string('track', 100)->nullable();
            $table->string('strand', 100)->nullable();

            // Student type
            $table->string('student_type', 20)->default('regular')->comment('regular, irregular_shs');
            $table->string('enrollment_type', 50)->default('new')->comment('new, return, transferee');
            $table->string('gender', 20)->nullable();

            // Section
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('section_name', 100)->nullable();

            // Dates & Status
            $table->date('enrollment_date')->nullable();
            $table->string('enrollment_status', 50)->default('enrolled');
            $table->string('assignment_status', 50)->default('pending')
                  ->comment('pending, assigned, fully_scheduled, incomplete');
            $table->timestamp('assigned_at')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();

            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->nullOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete();

            $table->unique(['student_id', 'school_year'], 'unique_student_schoolyear');
            $table->index('school_year');
            $table->index('assignment_status');
            $table->index('student_type');
            $table->index('grade_level');
        });
    }

    public function down(): void { Schema::dropIfExists('student_enrollment'); }
};