<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_security', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->tinyInteger('attempts')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->boolean('alert_sent')->default(false);
            $table->boolean('requires_otp')->default(false);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_security');
    }
};
