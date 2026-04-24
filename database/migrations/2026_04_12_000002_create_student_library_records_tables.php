<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_library_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('school_year', 20);
            $table->string('status', 30)->default('no_record'); // no_record, pending, overdue, cleared
            $table->string('cleared_by', 150)->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'school_year']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::create('student_library_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->string('book_title', 255);
            $table->string('book_id', 100)->nullable();
            $table->date('date_borrowed');
            $table->date('due_date');
            $table->date('date_returned')->nullable();
            $table->decimal('fines', 8, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('librarian_name', 150)->nullable();
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('student_library_records')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_library_books');
        Schema::dropIfExists('student_library_records');
    }
};
