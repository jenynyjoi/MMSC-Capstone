<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->json('viewers')->default('["All"]'); // e.g. ["All"] or ["Students","Teachers"]
            $table->enum('importance', ['low', 'medium', 'high'])->default('low');
            $table->string('attachment')->nullable(); // stored file path
            $table->string('posted_by')->default('Admin');
            $table->string('school_year')->default('2025-2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
