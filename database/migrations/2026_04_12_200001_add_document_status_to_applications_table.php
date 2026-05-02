<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'psa_status'))
                $table->string('psa_status', 20)->default('not_uploaded')->after('psa_path');
            if (!Schema::hasColumn('applications', 'psa_submitted'))
                $table->boolean('psa_submitted')->default(false)->after('psa_status');

            if (!Schema::hasColumn('applications', 'report_card_status'))
                $table->string('report_card_status', 20)->default('not_uploaded')->after('report_card_path');
            if (!Schema::hasColumn('applications', 'report_card_submitted'))
                $table->boolean('report_card_submitted')->default(false)->after('report_card_status');

            if (!Schema::hasColumn('applications', 'good_moral_status'))
                $table->string('good_moral_status', 20)->default('not_uploaded')->after('good_moral_path');
            if (!Schema::hasColumn('applications', 'good_moral_submitted'))
                $table->boolean('good_moral_submitted')->default(false)->after('good_moral_status');

            if (!Schema::hasColumn('applications', 'medical_uploaded'))
                $table->boolean('medical_uploaded')->default(false)->after('good_moral_submitted');
            if (!Schema::hasColumn('applications', 'medical_filename'))
                $table->string('medical_filename', 255)->nullable()->after('medical_uploaded');
            if (!Schema::hasColumn('applications', 'medical_path'))
                $table->string('medical_path', 500)->nullable()->after('medical_filename');
            if (!Schema::hasColumn('applications', 'medical_status'))
                $table->string('medical_status', 20)->default('not_uploaded')->after('medical_path');
            if (!Schema::hasColumn('applications', 'medical_submitted'))
                $table->boolean('medical_submitted')->default(false)->after('medical_status');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'psa_status', 'psa_submitted',
                'report_card_status', 'report_card_submitted',
                'good_moral_status', 'good_moral_submitted',
                'medical_uploaded', 'medical_filename', 'medical_path',
                'medical_status', 'medical_submitted',
            ]);
        });
    }
};
