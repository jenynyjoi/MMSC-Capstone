<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grade_curriculum_configs', function (Blueprint $table) {
            $table->id();
            $table->string('grade_level', 30);
            $table->string('program_level', 50);
            $table->string('school_year', 20);
            $table->unsignedSmallInteger('total_subjects_required')->default(0);
            $table->timestamps();
            $table->unique(['grade_level', 'school_year']);
        });

        Schema::create('grade_curriculum_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_config_id')
                  ->constrained('grade_curriculum_configs')->onDelete('cascade');
            $table->foreignId('subject_id')
                  ->constrained('subjects')->onDelete('cascade');
            $table->decimal('hours_per_week', 5, 2)->nullable();
            $table->unsignedSmallInteger('meetings_per_week')->nullable();
            $table->decimal('hours_per_meeting', 5, 2)->nullable();
            $table->string('subject_type', 50)->nullable();
            $table->boolean('is_required')->default(true);
            $table->string('semester', 30)->nullable();
            $table->timestamps();
            $table->unique(['curriculum_config_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_curriculum_subjects');
        Schema::dropIfExists('grade_curriculum_configs');
    }
};
