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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('type', ['Appartement', 'Villa', 'Maison', 'Studio', 'Duplex']);
            $table->integer('surface');
            $table->string('reference')->unique();
            $table->json('images')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('validated')->default(false);
            $table->date('published_date')->nullable();
            
            // Location details
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Property details
            $table->integer('rooms')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('floor')->nullable();
            $table->integer('total_floors')->nullable();
            $table->boolean('parking')->default(false);
            $table->boolean('elevator')->default(false);
            $table->boolean('terrace')->default(false);
            $table->boolean('garden')->default(false);
            $table->json('features')->nullable();
            
            // VEFA details
            $table->boolean('is_vefa')->default(false);
            $table->date('delivery_date')->nullable();
            $table->string('construction_progress')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
