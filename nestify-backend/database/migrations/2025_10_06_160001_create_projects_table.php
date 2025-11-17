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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promoter_id')->constrained()->onDelete('cascade');
            
            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('reference')->unique();
            
            // Location
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Project Details
            $table->enum('status', ['planning', 'under_construction', 'near_completion', 'completed', 'on_hold'])->default('planning');
            $table->date('launch_date')->nullable();
            $table->date('expected_delivery')->nullable();
            $table->string('construction_progress')->nullable(); // "75% complété"
            $table->integer('construction_progress_percentage')->nullable(); // 75
            
            // Building Information
            $table->integer('total_units')->default(0);
            $table->integer('available_units')->default(0);
            $table->integer('sold_units')->default(0);
            $table->integer('reserved_units')->default(0);
            $table->integer('total_floors')->nullable();
            $table->integer('buildings_count')->default(1);
            
            // Pricing
            $table->decimal('starting_price', 12, 2)->nullable();
            $table->decimal('average_price_per_sqm', 10, 2)->nullable();
            $table->decimal('price_range_min', 12, 2)->nullable();
            $table->decimal('price_range_max', 12, 2)->nullable();
            
            // Features & Amenities
            $table->json('amenities')->nullable(); // ["Piscine", "Salle de sport", "Gardiennage"]
            $table->json('nearby_facilities')->nullable(); // ["Écoles", "Centres commerciaux"]
            $table->json('tags')->nullable(); // ["VEFA", "Neuf", "Standing"]
            
            // Media
            $table->json('images')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('floor_plans')->nullable();
            $table->json('virtual_tours')->nullable();
            
            // Publication & Visibility
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            
            // SEO & Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('seo_keywords')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

