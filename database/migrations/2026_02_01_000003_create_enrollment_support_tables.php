<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Section Assignment History ──────────────────────
        Schema::create('section_assignment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('school_year', 20);
            $table->unsignedBigInteger('old_section_id')->nullable();
            $table->string('old_section_name', 100)->nullable();
            $table->unsignedBigInteger('new_section_id');
            $table->string('new_section_name', 100)->nullable();
            $table->string('assignment_type', 50)->nullable()->comment('individual, bulk, edit, auto_balance');
            $table->string('bulk_batch_id', 100)->nullable();
            $table->string('change_reason', 255)->nullable();
            $table->string('request_type', 50)->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->index(['student_id', 'school_year']);
        });

        // ── Bulk Assignment Batches ─────────────────────────
        Schema::create('bulk_assignment_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id', 100)->unique();
            $table->string('school_year', 20);
            $table->string('grade_level', 50);
            $table->string('student_type', 20)->nullable();
            $table->integer('total_students_selected')->default(0);
            $table->integer('total_students_assigned')->default(0);
            $table->integer('total_students_failed')->default(0);
            $table->string('distribution_method', 50)->nullable()->comment('single_section, distribute_across, split_sections');
            $table->unsignedBigInteger('selected_section_id')->nullable();
            $table->json('distribution_map')->nullable();
            $table->boolean('had_insufficient_capacity')->default(false);
            $table->string('handling_option', 50)->nullable()->comment('split, new_section, assign_available');
            $table->string('batch_status', 50)->default('processing');
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->index('batch_id');
            $table->index('batch_status');
        });

        // ── Bulk Assignment Details ─────────────────────────
        Schema::create('bulk_assignment_details', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id', 100);
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('student_name', 255)->nullable();
            $table->unsignedBigInteger('assigned_section_id')->nullable();
            $table->string('assigned_section_name', 100)->nullable();
            $table->string('assignment_status', 50)->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->index('batch_id');
            $table->index('assignment_status');
        });

        // ── Section Change Requests ─────────────────────────
        Schema::create('section_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('student_name', 255)->nullable();
            $table->unsignedBigInteger('current_section_id')->nullable();
            $table->string('current_section_name', 100)->nullable();
            $table->unsignedBigInteger('requested_section_id')->nullable();
            $table->string('requested_section_name', 100)->nullable();
            $table->string('request_type', 50)->nullable()->comment('parent_request, schedule_conflict, academic_need');
            $table->text('request_reason')->nullable();
            $table->string('request_status', 50)->default('pending')->comment('pending, approved, denied');
            $table->text('admin_remarks')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->index('student_id');
            $table->index('request_status');
            $table->timestamps();
        });

        // ── Section Balance Log ─────────────────────────────
        Schema::create('section_balance_log', function (Blueprint $table) {
            $table->id();
            $table->string('school_year', 20);
            $table->string('grade_level', 50);
            $table->string('batch_id', 100)->nullable();
            $table->json('before_distribution')->nullable();
            $table->json('after_distribution')->nullable();
            $table->json('new_sections_created')->nullable();
            $table->integer('total_students_processed')->default(0);
            $table->integer('total_sections_affected')->default(0);
            $table->integer('new_sections_created_count')->default(0);
            $table->integer('max_capacity')->default(30);
            $table->integer('min_capacity')->default(20);
            $table->string('balance_status', 50)->default('completed');
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->index(['school_year', 'grade_level']);
        });

        // ── Assignment Notifications ────────────────────────
        Schema::create('assignment_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('notification_type', 50)->nullable()->comment('section_assigned, section_changed, subject_assigned');
            $table->string('recipient_email', 255)->nullable();
            $table->string('recipient_type', 20)->nullable()->comment('student, parent, teacher');
            $table->string('email_subject', 500)->nullable();
            $table->text('email_body')->nullable();
            $table->string('status', 50)->default('pending')->comment('pending, sent, failed');
            $table->text('error_message')->nullable();
            $table->timestamp('queued_at')->useCurrent();
            $table->timestamp('sent_at')->nullable();
            $table->index('status');
            $table->index('recipient_email');
        });

        // ── Section Waitlist ────────────────────────────────
        Schema::create('section_waitlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->string('school_year', 20);
            $table->string('priority_level', 20)->default('medium');
            $table->string('priority_reason', 255)->nullable();
            $table->integer('waitlist_position')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamp('added_at')->useCurrent();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->index('section_id');
            $table->index('status');
        });

        // ── Audit Log ───────────────────────────────────────
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('action', 100)->nullable();
            $table->string('action_type', 50)->nullable()->comment('individual, bulk, edit');
            $table->string('action_category', 50)->nullable()->comment('assignment, edit, creation');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->json('details')->nullable();
            $table->string('batch_id', 100)->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->index('student_id');
            $table->index('action');
            $table->index('performed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('section_waitlist');
        Schema::dropIfExists('assignment_notifications');
        Schema::dropIfExists('section_balance_log');
        Schema::dropIfExists('section_change_requests');
        Schema::dropIfExists('bulk_assignment_details');
        Schema::dropIfExists('bulk_assignment_batches');
        Schema::dropIfExists('section_assignment_history');
    }
};