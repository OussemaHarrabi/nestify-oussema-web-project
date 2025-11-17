<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable()->after('last_contacted_at');
            }
            if (!Schema::hasColumn('leads', 'qualified_at')) {
                $table->timestamp('qualified_at')->nullable()->after('contacted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['contacted_at', 'qualified_at']);
        });
    }
};