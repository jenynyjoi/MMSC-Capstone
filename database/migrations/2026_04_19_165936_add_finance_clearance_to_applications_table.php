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
            $table->enum('finance_clearance', ['not_set', 'cleared', 'pending', 'hold'])
                  ->default('not_set')->after('submitted_at');
            $table->text('finance_clearance_notes')->nullable()->after('finance_clearance');
            $table->timestamp('finance_clearance_updated_at')->nullable()->after('finance_clearance_notes');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['finance_clearance', 'finance_clearance_notes', 'finance_clearance_updated_at']);
        });
    }
};
