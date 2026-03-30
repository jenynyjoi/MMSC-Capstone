<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique()->comment('APP-2026-001234');

            // ── STEP 1: GRADE LEVEL ──
            $table->enum('applied_level', ['Elementary', 'Junior High School', 'Senior High School']);
            $table->string('incoming_grade_level', 50);
            $table->enum('student_status', ['Old', 'New'])->default('New');
            $table->enum('student_category', ['Regular Payee', 'SHS Voucher Recipient', 'ESC Grantee'])->default('Regular Payee');
            $table->boolean('is_transferee')->default(false);
            $table->string('previous_school', 255)->nullable();
            $table->text('previous_school_address')->nullable();

            // ── STEP 2: PERSONAL INFO ──
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 20)->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->date('date_of_birth');
            $table->string('lrn', 50)->nullable()->comment('Learner Reference Number');
            $table->string('nationality', 100)->nullable()->default('Filipino');
            $table->string('mother_tongue', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('personal_email', 255);
            $table->string('mobile_number', 20);
            $table->text('home_address');
            $table->string('city', 100)->nullable();
            $table->string('zip_code', 10)->nullable();

            // ── STEP 3: PARENT/GUARDIAN ──
            $table->string('father_name', 255)->nullable();
            $table->string('father_contact', 20)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('mother_maiden_name', 255)->nullable();
            $table->string('mother_contact', 20)->nullable();
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_relationship', 50)->nullable();
            $table->string('guardian_contact', 50)->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_occupation', 100)->nullable();
            $table->string('guardian_email', 255)->nullable();
            $table->string('emergency_contact_number', 20)->nullable();

            // ── STEP 4: DOCUMENTS ──
            $table->boolean('psa_uploaded')->default(false);
            $table->string('psa_filename', 255)->nullable();
            $table->string('psa_path', 500)->nullable();
            $table->boolean('report_card_uploaded')->default(false);
            $table->string('report_card_filename', 255)->nullable();
            $table->string('report_card_path', 500)->nullable();
            $table->boolean('good_moral_uploaded')->default(false);
            $table->string('good_moral_filename', 255)->nullable();
            $table->string('good_moral_path', 500)->nullable();

            // ── SHS ACADEMIC ──
            $table->string('track', 100)->nullable();
            $table->string('strand', 100)->nullable();
            $table->string('pathway', 100)->nullable();

            // ── SCHOOL YEAR ──
            $table->string('school_year', 20)->default('2026-2027');

            // ── STATUS ──
            $table->enum('application_status', ['pending', 'pre_approved', 'approved', 'rejected', 'incomplete'])->default('pending');

            // ── CONSENT ──
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_date')->nullable();
            $table->string('parent_name_consent', 255)->nullable();

            // ── AUDIT ──
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index('reference_number');
            $table->index('application_status');
            $table->index('personal_email');
            $table->index('lrn');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};