<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_profiles', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('teacher_profiles', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('teacher_profiles', 'middle_name')) {
                $table->string('middle_name', 100)->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('teacher_profiles', 'contact_number')) {
                $table->string('contact_number', 30)->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('teacher_profiles', 'personal_email')) {
                $table->string('personal_email', 255)->nullable()->after('contact_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'middle_name', 'contact_number', 'personal_email']);
        });
    }
};
