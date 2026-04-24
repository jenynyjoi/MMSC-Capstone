<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_finance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('reference_number', 50)->nullable()->index(); // for pre-enrollment (application stage)
            $table->string('school_year', 20)->nullable();
            $table->string('grade_level', 50)->nullable();
            $table->string('student_category', 50)->nullable(); // Regular, ESC, SHS Voucher, Public Completer
            $table->string('payment_plan', 10)->nullable();     // A, B, C, D
            $table->decimal('enrollment_fee', 10, 2)->default(0);
            $table->decimal('monthly_amount', 10, 2)->default(0);
            $table->integer('monthly_months')->default(0);
            $table->decimal('misc_fee', 10, 2)->default(0);
            $table->decimal('referral_discount', 10, 2)->default(0);
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('finance_clearance', 20)->default('pending'); // pending, cleared, overdue
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_finance');
    }
};
