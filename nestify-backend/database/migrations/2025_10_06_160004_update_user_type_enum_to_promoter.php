<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update user_type enum to include 'promoter' instead of 'agency'
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('admin', 'promoter', 'client') DEFAULT 'client'");
        
        // Update existing 'agency' users to 'promoter'
        DB::table('users')->where('user_type', 'agency')->update(['user_type' => 'promoter']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'promoter' users back to 'agency'
        DB::table('users')->where('user_type', 'promoter')->update(['user_type' => 'agency']);
        
        // Revert enum back to original
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('admin', 'agency', 'client') DEFAULT 'client'");
    }
};

