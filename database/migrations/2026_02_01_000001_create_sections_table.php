<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_id', 50)->unique()->nullable()->comment('e.g., SEC-2026-001');
            $table->string('school_year', 20);
            $table->string('grade_level', 50);
            $table->string('section_name', 100);
            $table->string('full_name', 150)->nullable()->comment('e.g., Grade 7 - A, STEM 11 - A');

            // Capacity
            $table->integer('capacity')->default(30);
            $table->integer('current_enrollment')->default(0);
            $table->integer('waitlist_count')->default(0);

            // Location & Staff
            $table->string('room', 50)->nullable();
            $table->unsignedBigInteger('homeroom_adviser_id')->nullable();
            $table->string('homeroom_adviser_name', 255)->nullable();
            $table->string('adviser_status', 50)->default('assigned')->comment('assigned, tba');

            // SHS
            $table->string('track', 100)->nullable();
            $table->string('strand', 100)->nullable();
            $table->string('program_level', 50)->nullable()->comment('Elementary, Junior High School, Senior High School');

            // Status
            $table->string('availability', 50)->default('available')->comment('available, full, near_capacity, below_minimum');
            $table->string('section_status', 50)->default('active')->comment('active, inactive, archived');
            $table->string('section_type', 20)->default('regular')->comment('regular, special, sped');

            // Subject sections (irregular)
            $table->boolean('is_subject_section')->default(false);
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_name', 255)->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('teacher_name', 255)->nullable();
            $table->string('schedule_day', 20)->nullable();
            $table->time('schedule_time_start')->nullable();
            $table->time('schedule_time_end')->nullable();

            $table->timestamps();

            $table->index(['school_year', 'grade_level']);
            $table->index('availability');
            $table->index('is_subject_section');
            $table->index('section_status');
        });
    }

    public function down(): void { Schema::dropIfExists('sections'); }
};