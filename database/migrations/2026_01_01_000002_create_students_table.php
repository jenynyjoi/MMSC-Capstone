<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50)->unique()->comment('2026-001');
            $table->string('reference_number', 50)->nullable()->comment('Original application reference');

            // ── PERMANENT PERSONAL INFO ──
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 20)->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth', 255)->nullable();
            $table->string('nationality', 100)->nullable()->default('Filipino');
            $table->string('mother_tongue', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('lrn', 50)->nullable()->comment('Learner Reference Number');

            // ── CONTACT ──
            $table->text('home_address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('personal_email', 255)->nullable();
            $table->string('school_email', 255)->unique()->nullable()->comment('firstname.lastname@mmsc.edu.ph');

            // ── PARENT/GUARDIAN ──
            $table->string('father_name', 255)->nullable();
            $table->string('father_occupation', 100)->nullable();
            $table->string('father_contact', 20)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('mother_maiden_name', 255)->nullable();
            $table->string('mother_occupation', 100)->nullable();
            $table->string('mother_contact', 20)->nullable();
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_relationship', 50)->nullable();
            $table->string('guardian_contact', 50)->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_occupation', 100)->nullable();
            $table->string('guardian_email', 255)->nullable();

            // ── ENROLLMENT INFO (set per year) ──
            $table->string('school_year', 20)->nullable();
            $table->string('applied_level', 100)->nullable();
            $table->string('grade_level', 50)->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('section_name', 100)->nullable();
            $table->string('track', 100)->nullable();
            $table->string('strand', 100)->nullable();
            $table->string('admission_type', 50)->nullable()->comment('New, Return, Transferee');
            $table->enum('student_category', ['Regular Payee', 'SHS Voucher Recipient', 'ESC Grantee'])->default('Regular Payee');
            $table->date('enrollment_date')->nullable();
            $table->timestamp('enrolled_at')->nullable();

            // ── STATUS ──
            $table->string('student_status', 50)->default('active')->comment('active, inactive, withdrawn, graduated');
            $table->string('academic_status', 50)->default('in_progress')->comment('passed, failed, in_progress');
            $table->string('clearance_status', 50)->default('pending')->comment('pending, cleared');
            $table->string('enrollment_status', 50)->default('enrolled');

            // ── PORTAL ACCOUNT ──
            $table->unsignedBigInteger('user_id')->nullable()->comment('Links to users table');
            $table->boolean('portal_account_created')->default(false);
            $table->timestamp('account_created_at')->nullable();
            $table->boolean('password_changed')->default(false);
            $table->timestamp('last_login')->nullable();

            // ── AUDIT ──
            $table->timestamps();

            $table->index('student_id');
            $table->index('lrn');
            $table->index('school_email');
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};