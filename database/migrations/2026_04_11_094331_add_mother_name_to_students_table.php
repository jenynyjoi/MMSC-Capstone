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
        if (!Schema::hasColumn('students', 'mother_name')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('mother_name', 255)->nullable()->after('father_contact');
            });
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('mother_name');
        });
    }
};
