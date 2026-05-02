<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add online_reference to student_payments (for GCash/PayMaya/PayPal proof)
        Schema::table('student_payments', function (Blueprint $table) {
            $table->string('online_reference', 100)->nullable()->after('payment_method')
                  ->comment('Reference/transaction number for online payments');
        });

        // Finance reminder notification queue (mirrors assignment_notifications pattern)
        Schema::create('finance_reminder_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_finance_id')->nullable();
            $table->string('reminder_type', 50)->nullable()->comment('overdue, upcoming, general');
            $table->string('recipient_email', 255)->nullable();
            $table->string('recipient_type', 20)->nullable()->comment('student, guardian');
            $table->string('email_subject', 500)->nullable();
            $table->text('email_body')->nullable();
            $table->string('status', 50)->default('pending')->comment('pending, sent, failed');
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('queued_by')->nullable();
            $table->timestamp('queued_at')->useCurrent();
            $table->timestamp('sent_at')->nullable();
            $table->index('status');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_reminder_notifications');
        Schema::table('student_payments', function (Blueprint $table) {
            $table->dropColumn('online_reference');
        });
    }
};
