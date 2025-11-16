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
        Schema::create('duel_turns', function (Blueprint $table) {
            $table->id();

            // Duel reference
            $table->foreignId('duel_id')->constrained('duels')->onDelete('cascade');

            // Turn info
            $table->integer('turn_number')->default(1);
            $table->integer('player_id')->unsigned();

            // Action
            $table->enum('action_type', ['spell', 'defend', 'item', 'flee'])->default('spell');
            $table->integer('spell_id')->unsigned()->nullable();
            $table->string('item_used')->nullable();

            // Results
            $table->integer('damage_dealt')->default(0);
            $table->integer('healing_done')->default(0);
            $table->integer('mana_used')->default(0);
            $table->integer('mana_restored')->default(0);

            // Combat flags
            $table->boolean('is_critical')->default(false);
            $table->boolean('is_dodged')->default(false);
            $table->boolean('is_blocked')->default(false);

            // Effects applied (JSON for buffs/debuffs)
            $table->json('effects_applied')->nullable();

            // Description of what happened
            $table->text('description')->nullable();

            // Player health/mana after this turn
            $table->integer('player_health_after')->default(0);
            $table->integer('player_mana_after')->default(0);
            $table->integer('opponent_health_after')->default(0);
            $table->integer('opponent_mana_after')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('spell_id')->references('id')->on('spells')->onDelete('set null');

            // Indexes
            $table->index(['duel_id', 'turn_number']);
            $table->index('player_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duel_turns');
    }
};
