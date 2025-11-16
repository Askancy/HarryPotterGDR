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
        Schema::create('duels', function (Blueprint $table) {
            $table->id();

            // Players
            $table->integer('challenger_id')->unsigned();
            $table->integer('opponent_id')->unsigned();
            $table->integer('winner_id')->unsigned()->nullable();

            // Status
            $table->enum('status', ['pending', 'active', 'completed', 'declined', 'expired', 'fled'])->default('pending');

            // Location
            $table->integer('location_id')->unsigned()->nullable();

            // Turn tracking
            $table->integer('current_turn')->default(0);
            $table->integer('current_player_id')->unsigned()->nullable();

            // Initial stats (snapshot at duel start)
            $table->integer('challenger_health')->default(0);
            $table->integer('challenger_mana')->default(0);
            $table->integer('opponent_health')->default(0);
            $table->integer('opponent_mana')->default(0);

            // Current stats (during duel)
            $table->integer('challenger_current_health')->default(0);
            $table->integer('challenger_current_mana')->default(0);
            $table->integer('opponent_current_health')->default(0);
            $table->integer('opponent_current_mana')->default(0);

            // Effects tracking (JSON for buffs/debuffs)
            $table->json('challenger_effects')->nullable();
            $table->json('opponent_effects')->nullable();

            // Rewards
            $table->integer('exp_reward')->default(0);
            $table->integer('money_reward')->default(0);
            $table->integer('house_points_reward')->default(0);

            // Flags
            $table->boolean('is_practice')->default(false);
            $table->boolean('challenger_defending')->default(false);
            $table->boolean('opponent_defending')->default(false);

            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('challenger_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('opponent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('current_player_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['challenger_id', 'opponent_id']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duels');
    }
};
