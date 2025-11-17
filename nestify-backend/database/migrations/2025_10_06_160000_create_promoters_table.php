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
        Schema::create('promoters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Company Information
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            
            // Contact Information
            $table->string('primary_phone');
            $table->json('additional_phones')->nullable();
            $table->string('primary_email');
            $table->json('additional_emails')->nullable();
            
            // Business Information
            $table->string('license_number')->unique()->nullable();
            $table->date('established_date')->nullable();
            $table->integer('employee_count')->nullable();
            $table->json('specializations')->nullable(); // ["Résidentiel", "Luxe", "VEFA"]
            
            // Address Information
            $table->string('headquarters_address')->nullable();
            $table->string('headquarters_city')->nullable();
            $table->json('branch_offices')->nullable(); // [{city, address, phone}]
            
            // Statistics & Ratings
            $table->integer('total_projects')->default(0);
            $table->integer('completed_projects')->default(0);
            $table->integer('active_projects')->default(0);
            $table->float('rating', 3, 2)->nullable();
            $table->integer('review_count')->default(0);
            
            // Verification & Status
            $table->boolean('verified')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoters');
    }
};

