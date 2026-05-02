<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add referral_count and referral_no_referral flag to student_finance
        Schema::table('student_finance', function (Blueprint $table) {
            $table->tinyInteger('referral_count')->default(0)->after('referral_discount');
            $table->boolean('no_referral')->default(false)->after('referral_count'); // SHS Option C
            $table->boolean('is_full_payment')->default(false)->after('no_referral'); // Plan A
        });

        // New student_payments table for individual payment transactions
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_finance_id')->index();
            $table->string('receipt_number', 30)->unique();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method', 30)->default('cash'); // cash, gcash, bank_transfer, check
            $table->json('month_ids')->nullable(); // IDs of student_payment_months this covers
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign('student_finance_id')->references('id')->on('student_finance')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
        Schema::table('student_finance', function (Blueprint $table) {
            $table->dropColumn(['referral_count', 'no_referral', 'is_full_payment']);
        });
    }
};
