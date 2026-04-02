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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('userid');
            $table->date('log_date')->nullable();
            $table->dateTime('punch_in')->nullable();
            $table->dateTime('punch_out')->nullable();
            $table->integer('status')->nullable();
            $table->string('device_id')->nullable();
            $table->string('log_status')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'userid', 'punch_in', 'punch_out'], 'company_user_time_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
