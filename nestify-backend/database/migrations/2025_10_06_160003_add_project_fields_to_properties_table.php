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
            // Add project relationship
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            
            // Add new property fields
            $table->string('unit_number')->nullable(); // "A101", "B205"
            $table->string('building_name')->nullable(); // "Building A", "Tower 1"
            $table->enum('availability_status', ['available', 'reserved', 'sold', 'not_available'])->default('available');
            
            // Enhanced VEFA fields
            $table->integer('construction_progress_percentage')->nullable(); // 75
            $table->json('payment_schedule')->nullable(); // Payment plan details
            $table->json('guarantees')->nullable(); // ["Décennale", "Biennale"]
            
            // Enhanced features
            $table->json('interior_features')->nullable(); // ["Parquet", "Marbre", "Cuisine équipée"]
            $table->json('exterior_features')->nullable(); // ["Balcon", "Terrasse", "Jardin"]
            $table->json('building_features')->nullable(); // ["Ascenseur", "Gardiennage", "Piscine"]
            
            // Pricing details
            $table->decimal('price_per_sqm', 10, 2)->nullable();
            $table->decimal('monthly_charges', 10, 2)->nullable(); // Charges mensuelles
            $table->decimal('notary_fees', 10, 2)->nullable();
            $table->decimal('registration_fees', 10, 2)->nullable();
            
            // Media enhancements
            $table->string('floor_plan_image')->nullable();
            $table->json('virtual_tour_urls')->nullable();
            $table->json('video_urls')->nullable();
            
            // SEO & Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('seo_keywords')->nullable();
            
            // Additional tracking
            $table->integer('favorite_count')->default(0);
            $table->integer('contact_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn([
                'project_id',
                'unit_number',
                'building_name',
                'availability_status',
                'construction_progress_percentage',
                'payment_schedule',
                'guarantees',
                'interior_features',
                'exterior_features',
                'building_features',
                'price_per_sqm',
                'monthly_charges',
                'notary_fees',
                'registration_fees',
                'floor_plan_image',
                'virtual_tour_urls',
                'video_urls',
                'meta_title',
                'meta_description',
                'seo_keywords',
                'favorite_count',
                'contact_count',
                'last_viewed_at'
            ]);
        });
    }
};

