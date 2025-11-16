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
        Schema::create('duel_statistics', function (Blueprint $table) {
            $table->id();

            // User reference
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Overall stats
            $table->integer('total_duels')->default(0);
            $table->integer('duels_won')->default(0);
            $table->integer('duels_lost')->default(0);
            $table->integer('duels_fled')->default(0);
            $table->integer('practice_duels')->default(0);

            // Combat stats
            $table->bigInteger('total_damage_dealt')->default(0);
            $table->bigInteger('total_damage_received')->default(0);
            $table->bigInteger('total_healing_done')->default(0);
            $table->integer('total_spells_cast')->default(0);
            $table->integer('total_critical_hits')->default(0);
            $table->integer('total_dodges')->default(0);

            // Favorite spell
            $table->foreignId('favorite_spell_id')->nullable()->constrained('spells')->onDelete('set null');
            $table->integer('favorite_spell_uses')->default(0);

            // Streaks
            $table->integer('current_winning_streak')->default(0);
            $table->integer('longest_winning_streak')->default(0);
            $table->integer('current_losing_streak')->default(0);
            $table->integer('longest_losing_streak')->default(0);

            // Ranking
            $table->integer('ranking_points')->default(1000); // ELO-style rating
            $table->integer('rank_position')->nullable();

            // Records
            $table->integer('highest_damage_single_spell')->default(0);
            $table->integer('fastest_victory_turns')->nullable();
            $table->integer('longest_duel_turns')->default(0);

            // House duels
            $table->integer('house_duels_won')->default(0);
            $table->integer('house_points_earned')->default(0);

            // Rewards earned
            $table->bigInteger('total_exp_earned')->default(0);
            $table->bigInteger('total_money_earned')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('ranking_points');
            $table->index('rank_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duel_statistics');
    }
};
