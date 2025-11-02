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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Promo code like "SUMMER20"
            $table->string('name'); // Display name
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2); // Percentage (15) or fixed amount (5.00)
            $table->decimal('minimum_order_amount', 10, 2)->nullable(); // Minimum order to use this code
            $table->integer('usage_limit_per_user')->default(1); // How many times each user can use
            $table->integer('total_usage_limit')->nullable(); // Total times this code can be used (null = unlimited)
            $table->integer('valid_for_days')->nullable(); // How many days from creation date (null = no expiration)
            $table->date('expires_at')->nullable(); // Calculated expiration date
            $table->boolean('is_active')->default(true);
            $table->boolean('send_email_to_users')->default(false); // Whether to email all users
            $table->timestamp('emailed_at')->nullable(); // Track if email was sent
            $table->json('applicable_products')->nullable(); // Array of product IDs (null = all products)
            $table->timestamps();
        });

        // Create promo code usage tracking table
        Schema::create('promo_code_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['promo_code_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_usages');
        Schema::dropIfExists('promo_codes');
    }
};