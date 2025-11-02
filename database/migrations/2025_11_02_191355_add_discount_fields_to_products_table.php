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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_discount')->default(false)->after('price');
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable()->after('has_discount');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            $table->date('discount_expires_at')->nullable()->after('discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_discount', 'discount_type', 'discount_value', 'discount_expires_at']);
        });
    }
};