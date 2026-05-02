<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── teacher_profiles ────────────────────────────────────────
        // Extends users table for teacher-specific info
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // FK → users.id
            $table->string('teacher_id_code', 50)->unique()->nullable(); // e.g. TCH-2026-001

            // Personal / Professional
            $table->string('academic_rank', 100)->nullable();       // Teacher I, II, Master Teacher
            $table->string('employment_status', 50)->nullable();     // Full Time, Part Time
            $table->string('status', 50)->default('active');         // active, inactive, resigned, on_leave
            $table->string('department', 100)->nullable();
            $table->text('specializations')->nullable();             // JSON array of subjects
            $table->text('grade_levels')->nullable();                // JSON array of grade levels
            $table->string('advisory_class', 100)->nullable();       // e.g. "7 - A"
            $table->string('school_year', 20)->nullable();

            // Availability
            $table->integer('weekly_days_available')->default(5);    // 1–7
            $table->time('available_from')->nullable();              // e.g. 07:00
            $table->time('available_to')->nullable();                // e.g. 17:00
            $table->time('lunch_start')->nullable();
            $table->time('lunch_end')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
            $table->index('department');
        });

        // teacher_load already exists from subjects migration, but add target_yearly_hours
        // (We alter it — use a separate migration step)
        if (Schema::hasTable('teacher_load') && !Schema::hasColumn('teacher_load', 'target_yearly_hours')) {
            Schema::table('teacher_load', function (Blueprint $table) {
                $table->decimal('target_yearly_hours', 6, 1)->default(0)->after('max_weekly_hours');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};