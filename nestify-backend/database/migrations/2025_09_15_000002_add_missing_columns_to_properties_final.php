<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('properties', 'governorate')) {
                $table->string('governorate', 100)->nullable()->after('city');
            }
            if (!Schema::hasColumn('properties', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('governorate');
            }
            if (!Schema::hasColumn('properties', 'status')) {
                $table->string('status', 50)->default('A Vendre')->after('type');
            }
            if (!Schema::hasColumn('properties', 'area')) {
                $table->decimal('area', 10, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('properties', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('validated');
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'governorate')) {
                $table->dropColumn('governorate');
            }
            if (Schema::hasColumn('properties', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
            if (Schema::hasColumn('properties', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('properties', 'area')) {
                $table->dropColumn('area');
            }
            if (Schema::hasColumn('properties', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
