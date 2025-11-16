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
        Schema::create('creature_species', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image')->nullable();

            // Classification
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->enum('danger_level', ['harmless', 'low', 'moderate', 'dangerous', 'extreme'])->default('harmless');
            $table->string('habitat')->nullable(); // Foresta, Montagna, Lago, etc.

            // Requirements
            $table->integer('required_level')->default(1);
            $table->integer('required_care_skill')->default(0); // Skill "Cura delle Creature Magiche"

            // Base stats
            $table->integer('base_health')->default(100);
            $table->integer('base_happiness')->default(50);
            $table->integer('base_hunger')->default(50);
            $table->integer('base_energy')->default(100);

            // Growth
            $table->integer('max_level')->default(10);
            $table->integer('growth_speed')->default(100); // Percentuale velocità crescita
            $table->integer('maturity_days')->default(7); // Giorni per maturare

            // Care needs
            $table->integer('hunger_rate')->default(10); // Fame ogni ora
            $table->integer('happiness_decay')->default(5); // Felicità persa ogni ora
            $table->integer('energy_consumption')->default(15); // Energia consumata

            // Special abilities
            $table->json('abilities')->nullable(); // Abilità speciali della creatura
            $table->json('drops')->nullable(); // Oggetti che può droppare

            // Breeding
            $table->boolean('can_breed')->default(true);
            $table->integer('breeding_cooldown_hours')->default(48);
            $table->json('compatible_species')->nullable(); // Specie compatibili per breeding

            // Economy
            $table->integer('purchase_price')->default(0); // Prezzo acquisto (0 = non acquistabile)
            $table->integer('sell_price')->default(0); // Prezzo vendita

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_rideable')->default(false);
            $table->boolean('can_battle')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('rarity');
            $table->index('required_level');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creature_species');
    }
};
