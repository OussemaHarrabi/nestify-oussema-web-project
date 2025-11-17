<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // User preferences for property search
            $table->json('preferences')->nullable()->after('is_active');
            $table->string('profile_picture')->nullable()->after('preferences');
            $table->text('bio')->nullable()->after('profile_picture');
            $table->string('city')->nullable()->after('bio');
            $table->string('address')->nullable()->after('city');
            $table->timestamp('last_login_at')->nullable()->after('address');
            
            // Admin specific fields
            $table->enum('admin_role', ['super_admin', 'content_moderator', 'support'])->nullable()->after('last_login_at');
            $table->json('permissions')->nullable()->after('admin_role');
        });

        // Create user_property_views table for tracking
        Schema::create('user_property_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // For anonymous users
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['user_id', 'property_id']);
            $table->index(['session_id', 'property_id']);
        });

        // Create user_searches table for analytics
        Schema::create('user_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->json('search_criteria');
            $table->integer('results_count');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['session_id']);
        });

        // Improve agencies table
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
            $table->json('business_hours')->nullable()->after('logo');
            $table->string('license_number')->nullable()->after('business_hours');
            $table->date('established_date')->nullable()->after('license_number');
            $table->integer('employee_count')->nullable()->after('established_date');
            $table->json('specializations')->nullable()->after('employee_count'); // residential, commercial, etc.
            $table->decimal('rating', 3, 2)->default(0)->after('specializations');
            $table->integer('review_count')->default(0)->after('rating');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'preferences', 'profile_picture', 'bio', 'city', 'address', 
                'last_login_at', 'admin_role', 'permissions'
            ]);
        });

        Schema::dropIfExists('user_property_views');
        Schema::dropIfExists('user_searches');

        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'logo', 'business_hours', 'license_number', 'established_date',
                'employee_count', 'specializations', 'rating', 'review_count'
            ]);
        });
    }
};
