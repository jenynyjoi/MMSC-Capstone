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
            // ESC Grantee / SHS Voucher eligibility fields
            $table->string('subsidy_prev_school_type', 50)->nullable()->after('student_category');
            $table->string('subsidy_certificate_no',  100)->nullable()->after('subsidy_prev_school_type');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['subsidy_prev_school_type', 'subsidy_certificate_no']);
        });
    }
};
