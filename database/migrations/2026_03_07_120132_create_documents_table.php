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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',[
                'organization',
                'agreements',
                'hr',
                'others'
            ])->default('others');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('folder');
            $table->foreignId('share_with')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
