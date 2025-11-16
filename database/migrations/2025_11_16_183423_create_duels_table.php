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
            $table->foreignId('challenger_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opponent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');

            // Status
            $table->enum('status', ['pending', 'active', 'completed', 'declined', 'expired', 'fled'])->default('pending');

            // Location
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

            // Turn tracking
            $table->integer('current_turn')->default(0);
            $table->foreignId('current_player_id')->nullable()->constrained('users')->onDelete('set null');

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
