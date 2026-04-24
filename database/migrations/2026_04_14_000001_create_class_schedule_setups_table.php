<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedule_setups', function (Blueprint $table) {
            $table->id();
            // Elementary, Junior High School, Senior High School, Preschool
            $table->string('level_type', 100);
            // Nullable = applies to whole program level; set = overrides for specific grade
            $table->string('grade_level', 50)->nullable()->comment('Grade 1, Grade 7, etc. — overrides level_type if set');
            // e.g. "07:00" (stored as H:i string for simplicity)
            $table->string('time_start', 5)->default('07:00');
            $table->string('time_end', 5)->default('17:00');
            // Slot duration in minutes (30 or 60)
            $table->unsignedSmallInteger('slot_duration')->default(60);
            // JSON array: [{start:"10:00", end:"10:15", label:"Recess"}, ...]
            $table->json('breaks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['level_type', 'grade_level'], 'unique_level_grade');
            $table->index('level_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedule_setups');
    }
};
