<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 50)->unique();
            $table->unsignedSmallInteger('capacity')->default(30);
            $table->string('room_type', 50)->default('Regular')
                  ->comment('Regular, Lab, Auditorium, Music Room, Art Room');
            $table->string('grade_level_type', 50)->nullable()
                  ->comment('Grade 1-3, Grade 4-6, Grade 7-10, Grade 11-12, All Levels');
            $table->string('homeroom_adviser', 100)->nullable();
            $table->string('availability_status', 30)->default('available')
                  ->comment('available, occupied, under_repair — auto-computed from subject_schedules');
            $table->string('room_status', 30)->default('active')
                  ->comment('active, inactive, under_maintenance');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('availability_status');
            $table->index('room_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
