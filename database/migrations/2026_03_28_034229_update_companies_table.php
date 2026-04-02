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
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'organization_id')) {
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('organization_id')->nullable()->change();
            }
            
            $table->string('company_name')->nullable()->after('organization_id');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->softDeletes();
            
            if (Schema::hasColumn('companies', 'name')) {
                // $table->dropColumn('name'); // Wait, if I drop it now and I have data, it will be lost. I'll just rename it later or keep it for now.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['organization_id', 'company_name', 'phone', 'email', 'logo', 'address', 'deleted_at']);
        });
    }
};
