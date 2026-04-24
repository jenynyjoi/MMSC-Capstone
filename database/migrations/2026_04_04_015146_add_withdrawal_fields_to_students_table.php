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
        Schema::table('students', function (Blueprint $table) {
            $table->string('withdrawal_reason', 255)->nullable()->after('enrollment_status');
            $table->date('withdrawal_date')->nullable()->after('withdrawal_reason');
            $table->text('withdrawal_details')->nullable()->after('withdrawal_date');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['withdrawal_reason', 'withdrawal_date', 'withdrawal_details']);
        });
    }
};
