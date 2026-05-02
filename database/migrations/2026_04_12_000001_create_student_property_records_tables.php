<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_property_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('school_year', 20);
            $table->string('status', 30)->default('for_issuance'); // for_issuance, issued, cleared, overdue
            $table->timestamp('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'school_year']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::create('student_property_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->string('item_name', 100);
            $table->boolean('issued')->default(false);
            $table->boolean('returned')->default(false);
            $table->boolean('damaged')->default(false);
            $table->decimal('replacement_fee', 8, 2)->default(0);
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('student_property_records')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_property_items');
        Schema::dropIfExists('student_property_records');
    }
};
