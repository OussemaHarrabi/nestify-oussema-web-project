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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promoter_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            
            // Lead Information
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            
            // Lead Type & Status
            $table->enum('type', ['contact_request', 'viewing_request', 'price_inquiry', 'general_inquiry'])->default('contact_request');
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'closed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // Additional Information
            $table->string('budget_range')->nullable(); // "200000-400000"
            $table->string('preferred_contact_method')->nullable(); // "email", "phone", "whatsapp"
            $table->json('preferences')->nullable(); // {"property_type": "Appartement", "bedrooms": 3}
            $table->text('notes')->nullable(); // Internal notes
            
            // Source & Tracking
            $table->string('source')->nullable(); // "website", "facebook", "referral"
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Follow-up
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();
            $table->integer('contact_attempts')->default(0);
            
            // Conversion
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->foreignId('converted_property_id')->nullable()->constrained('properties')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

