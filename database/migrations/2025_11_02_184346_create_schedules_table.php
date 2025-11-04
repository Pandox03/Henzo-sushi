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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->boolean('is_override')->default(false); // If true, this overrides default schedule
            $table->boolean('is_closed')->default(false); // If true, restaurant is closed this day
            $table->time('lunch_start')->nullable(); // e.g., 11:30
            $table->time('lunch_end')->nullable(); // e.g., 15:30
            $table->time('dinner_start')->nullable(); // e.g., 18:30
            $table->time('dinner_end')->nullable(); // e.g., 23:30
            $table->date('override_date')->nullable(); // For special dates like holidays
            $table->text('notes')->nullable(); // Admin notes about why this day is special
            $table->timestamps();
            
            // Ensure we only have one schedule per day
            $table->unique(['day_of_week', 'override_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};