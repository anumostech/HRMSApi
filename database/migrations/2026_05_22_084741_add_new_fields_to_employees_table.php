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
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('visa_type', ['company_visa', 'family_visa', 'other_visa'])->nullable();
            $table->boolean('is_skilled')->default(false);
            $table->string('education_qualification')->nullable();
            $table->json('additional_documents')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['visa_type', 'is_skilled', 'education_qualification', 'additional_documents']);
        });
    }
};
