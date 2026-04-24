<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payment_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_finance_id')->index();
            $table->string('month_name', 20);   // July, August, ...
            $table->smallInteger('month_number'); // 1–12
            $table->smallInteger('month_year');   // e.g. 2025
            $table->date('due_date');
            $table->decimal('amount_due', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('status', 20)->default('pending'); // pending, paid, overdue, partial
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('student_finance_id')->references('id')->on('student_finance')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payment_months');
    }
};
