<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('school_year')->default('2025-2026'); // e.g. "2025-2026"
            $table->date('date');
            $table->enum('day_type', [
                'regular',
                'holiday',
                'suspended',
                'early_dismissal',
                'exam_day',
                'school_event',
                'break',
            ])->default('regular');
            $table->string('event_title')->nullable();
            $table->text('description')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->time('early_dismissal_time')->nullable();
            $table->enum('attendance_rule', [
                'normal',
                'no_attendance_holiday',
                'no_attendance_suspension',
                'morning_only',
                'afternoon_only',
                'exam_present',
            ])->default('normal');
            $table->string('applies_to')->default('all'); // "all" or comma-separated grades
            $table->boolean('notify_teachers')->default(false);
            $table->boolean('notify_parents')->default(false);
            $table->boolean('add_to_public')->default(false);
            $table->boolean('send_reminder')->default(false);
            $table->timestamps();

            $table->unique(['school_year', 'date']); // one entry per date per SY
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_calendar_events');
    }
};