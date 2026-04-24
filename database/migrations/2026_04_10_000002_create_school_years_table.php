<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();          // e.g. "2025-2026"
            $table->date('start_date');                // first day of school
            $table->date('end_date');                  // last day of school
            $table->date('effective_date')->nullable(); // when enrollment/admissions opens
            $table->json('class_days')->default('[1,2,3,4,5]'); // 0=Sun…6=Sat
            $table->enum('status', ['active', 'upcoming', 'ended'])->default('upcoming');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_years');
    }
};
