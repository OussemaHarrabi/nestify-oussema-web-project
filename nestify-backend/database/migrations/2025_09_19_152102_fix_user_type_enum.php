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
        // Simply change user_type to a string column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('regular_user')->after('phone');
        });
        
        // Update existing users to have proper user types
        DB::table('users')->update(['user_type' => 'admin']);
        
        // Set the one agency user correctly
        if (DB::table('agencies')->count() > 0) {
            $agencyUserId = DB::table('agencies')->first()->user_id;
            DB::table('users')->where('id', $agencyUserId)->update(['user_type' => 'agency']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['client', 'agency', 'promoter', 'admin'])->default('client')->after('phone');
        });
    }
};
