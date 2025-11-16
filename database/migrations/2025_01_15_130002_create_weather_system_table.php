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
        Schema::create('weather_conditions', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id')->unsigned()->nullable();
            $table->string('map_slug')->nullable(); // Per retrocompatibilità con vecchie mappe

            $table->date('date');
            $table->enum('season', ['spring', 'summer', 'autumn', 'winter']);

            // Condizioni meteo
            $table->enum('condition', [
                'sunny', 'cloudy', 'rainy', 'stormy', 'snowy', 'foggy', 'windy', 'magical_storm'
            ])->default('sunny');

            $table->integer('temperature')->default(15); // Celsius
            $table->integer('precipitation')->default(0); // 0-100%
            $table->integer('wind_speed')->default(0); // km/h

            // Effetti gameplay
            $table->text('effects')->nullable(); // JSON con effetti: {"visibility": -20, "movement_speed": -10}
            $table->string('special_event')->nullable(); // Es: "aurora_borealis", "meteor_shower"

            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->index(['date', 'location_id']);
        });

        // Tabella calendario scolastico
        Schema::create('school_calendar', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year'); // Es: "2024-2025"
            $table->date('start_date');
            $table->date('end_date');

            // Trimestri/Periodi
            $table->date('term1_start')->nullable();
            $table->date('term1_end')->nullable();
            $table->date('term2_start')->nullable();
            $table->date('term2_end')->nullable();
            $table->date('term3_start')->nullable();
            $table->date('term3_end')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // NOTA: La tabella 'school_events' è già creata in create_school_calendar_system.php
        // Non viene ricreata qui per evitare duplicazioni
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // NOTA: 'school_events' non viene eliminata qui, è gestita in create_school_calendar_system.php
        Schema::dropIfExists('school_calendar');
        Schema::dropIfExists('weather_conditions');
    }
};
