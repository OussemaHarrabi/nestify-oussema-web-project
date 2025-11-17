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
        Schema::table('properties', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('properties', 'governorate')) {
                $table->string('governorate')->nullable()->after('city');
            }
            if (!Schema::hasColumn('properties', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('governorate');
            }
            if (!Schema::hasColumn('properties', 'status')) {
                $table->string('status')->default('A Vendre')->after('type');
            }
            if (!Schema::hasColumn('properties', 'area')) {
                $table->integer('area')->nullable()->after('status');
            }
            if (!Schema::hasColumn('properties', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('validated');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['governorate', 'postal_code', 'status', 'area', 'is_active']);
        });
    }
};
