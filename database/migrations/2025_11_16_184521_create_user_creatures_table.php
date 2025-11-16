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
        Schema::create('user_creatures', function (Blueprint $table) {
            $table->id();

            // Owner
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('species_id')->constrained('creature_species')->onDelete('cascade');

            // Naming
            $table->string('nickname')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');

            // Current stats
            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
            $table->integer('current_health')->default(100);
            $table->integer('max_health')->default(100);
            $table->integer('happiness')->default(50);
            $table->integer('hunger')->default(0); // 0 = sazio, 100 = affamato
            $table->integer('energy')->default(100);

            // Life stages
            $table->enum('life_stage', ['egg', 'baby', 'juvenile', 'adult', 'elder'])->default('baby');
            $table->integer('age_days')->default(0);
            $table->timestamp('hatched_at')->nullable();
            $table->timestamp('matured_at')->nullable();

            // Care tracking
            $table->timestamp('last_fed_at')->nullable();
            $table->timestamp('last_played_at')->nullable();
            $table->timestamp('last_trained_at')->nullable();
            $table->timestamp('last_cleaned_at')->nullable();

            // Breeding
            $table->boolean('is_fertile')->default(false);
            $table->timestamp('last_bred_at')->nullable();
            $table->foreignId('parent1_id')->nullable()->constrained('user_creatures')->onDelete('set null');
            $table->foreignId('parent2_id')->nullable()->constrained('user_creatures')->onDelete('set null');

            // Special
            $table->json('learned_abilities')->nullable(); // Abilità apprese
            $table->json('traits')->nullable(); // Tratti genetici
            $table->integer('total_interactions')->default(0);
            $table->integer('bond_level')->default(0); // 0-100 legame con il proprietario

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->enum('status', ['healthy', 'sick', 'injured', 'sleeping', 'dead'])->default('healthy');

            // Location
            $table->foreignId('current_habitat_id')->nullable()->constrained('locations')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes(); // Soft delete per le creature "rilasciate"

            // Indexes
            $table->index(['user_id', 'species_id']);
            $table->index('level');
            $table->index('life_stage');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_creatures');
    }
};
