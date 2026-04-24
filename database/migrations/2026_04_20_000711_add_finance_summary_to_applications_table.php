<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('finance_total_assessment', 10, 2)->nullable()->after('finance_clearance_updated_at');
            $table->decimal('finance_amount_paid', 10, 2)->nullable()->after('finance_total_assessment');
            $table->date('finance_next_due_date')->nullable()->after('finance_amount_paid');
            $table->string('finance_cleared_by', 150)->nullable()->after('finance_next_due_date');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['finance_total_assessment', 'finance_amount_paid', 'finance_next_due_date', 'finance_cleared_by']);
        });
    }
};
