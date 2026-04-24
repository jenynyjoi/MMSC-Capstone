<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('behavioral_clearance', 50)->default('pending')->after('clearance_status');
            $table->string('property_clearance',   50)->default('pending')->after('behavioral_clearance');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['behavioral_clearance', 'property_clearance']);
        });
    }
};
