<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('behavioral_records', function (Blueprint $table) {
            $table->id();

            // Student reference
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->string('school_year', 20);
            $table->string('grade_level', 50)->nullable();
            $table->string('section_name', 100)->nullable();

            // Incident info
            $table->date('incident_date');
            $table->string('behavior_type', 100);   // Bullying, Tardiness, Fighting, etc.
            $table->string('severity', 50);          // Minor, Moderate, Major, Critical
            $table->string('action_taken', 100);     // Warning, Counseling, Suspension, etc.
            $table->string('action_details')->nullable();
            $table->string('referral_to', 100)->nullable(); // Guidance Office, Principal, etc.

            // Narrative
            $table->text('description');
            $table->text('resolution_notes')->nullable();

            // Status
            $table->string('status', 50)->default('pending'); // pending, resolved, dismissed, escalated

            // Notification
            $table->boolean('parent_notified')->default(false);
            $table->timestamp('parent_notified_at')->nullable();

            // Audit
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index(['student_id', 'school_year']);
            $table->index('status');
            $table->index('behavior_type');
            $table->index('incident_date');
        });

        Schema::create('behavioral_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('behavioral_record_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('file_type', 50)->nullable();   // pdf, docx, jpg, png
            $table->unsignedInteger('file_size')->nullable(); // bytes
            $table->string('description')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('behavioral_record_id')
                ->references('id')->on('behavioral_records')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('behavioral_documents');
        Schema::dropIfExists('behavioral_records');
    }
};