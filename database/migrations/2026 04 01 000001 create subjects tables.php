<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── subjects ────────────────────────────────────────────
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code', 50)->unique();
            $table->string('subject_name', 255);
            $table->text('description')->nullable();

            // Classification
            $table->string('department', 100)->nullable();
            $table->string('grade_level', 50)->nullable();
            $table->string('program_level', 50)->nullable(); // ELEM, JHS, SHS
            $table->string('subject_type', 50)->nullable();  // Core, Specialized, Applied, Elective

            // Hour requirements (set once, read-only on allocation)
            $table->decimal('hours_per_meeting', 4, 1)->default(1);
            $table->integer('meetings_per_week')->default(1);
            // SHS Semester
            $table->boolean('has_semester')->default(false);
            $table->string('default_semester', 50)->nullable(); // 1st Semester, 2nd Semester, Full Year

            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('program_level');
            $table->index('grade_level');
            $table->index('department');
        });

        // ── section_allocation_config ───────────────────────────
        // One record per section per school year — tracks allocation progress
        Schema::create('section_allocation_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->string('school_year', 20);
            $table->integer('total_subjects_required')->default(0);
            $table->integer('total_subjects_allocated')->default(0);
            $table->string('allocation_status', 50)->default('pending'); // pending, in_progress, complete
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->unique(['section_id', 'school_year']);
            $table->index('allocation_status');
        });

        // ── subject_allocation ──────────────────────────────────
        // Which subject is assigned to which section, by whom
        Schema::create('subject_allocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('school_year', 20);
            $table->unsignedBigInteger('teacher_id')->nullable(); // users.id

            // Denormalised for speed (copied from subject master at time of allocation)
            $table->string('subject_code', 50);
            $table->string('subject_name', 255);
            $table->decimal('hours_per_week', 4, 1);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->unique(['section_id', 'subject_id', 'school_year'], 'unique_section_subject_sy');
            $table->index(['section_id', 'school_year']);
        });

        // ── subject_schedule ────────────────────────────────────
        // One row per meeting slot per allocated subject
        Schema::create('subject_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allocation_id');
            $table->string('day_of_week', 20);       // Monday … Friday
            $table->time('time_start');
            $table->time('time_end');
            $table->string('room', 100)->nullable();
            $table->timestamps();

            $table->foreign('allocation_id')->references('id')->on('subject_allocation')->onDelete('cascade');
            $table->index(['day_of_week', 'time_start', 'time_end']);
        });

        // ── teacher_load ────────────────────────────────────────
        Schema::create('teacher_load', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');  // users.id
            $table->string('school_year', 20);
            $table->decimal('max_weekly_hours', 5, 1)->default(40);
            $table->decimal('current_weekly_hours', 5, 1)->default(0);
            $table->timestamps();

            $table->unique(['teacher_id', 'school_year']);
        });

        // ── grade_components ────────────────────────────────────
        Schema::create('grade_components', function (Blueprint $table) {
            $table->id();
            $table->string('component_code', 50)->unique();
            $table->string('component_name', 100);
            $table->decimal('grade_percentage', 5, 2)->default(0);
            $table->string('grade_level', 50)->nullable(); // ELEM, JHS, SHS, or specific
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── assessments ─────────────────────────────────────────
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allocation_id');   // subject_allocation.id = class
            $table->unsignedBigInteger('component_id');    // grade_components.id
            $table->string('quarter', 50);                 // First, Second, Third, Fourth
            $table->string('assessment_name', 255);
            $table->decimal('max_score', 7, 2)->default(100);
            $table->date('assessment_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('allocation_id')->references('id')->on('subject_allocation')->onDelete('cascade');
            $table->foreign('component_id')->references('id')->on('grade_components')->onDelete('cascade');
            $table->index(['allocation_id', 'quarter']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('grade_components');
        Schema::dropIfExists('teacher_load');
        Schema::dropIfExists('subject_schedule');
        Schema::dropIfExists('subject_allocation');
        Schema::dropIfExists('section_allocation_config');
        Schema::dropIfExists('subjects');
    }
};