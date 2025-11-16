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
        Schema::create('creature_interactions', function (Blueprint $table) {
            $table->id();

            // References
            $table->integer('user_id')->unsigned();
            $table->foreignId('creature_id')->constrained('user_creatures')->onDelete('cascade');

            // Interaction type
            $table->enum('action', ['feed', 'play', 'train', 'clean', 'heal', 'breed', 'collect_drop'])->index();

            // Results
            $table->integer('experience_gained')->default(0);
            $table->integer('happiness_change')->default(0);
            $table->integer('hunger_change')->default(0);
            $table->integer('health_change')->default(0);
            $table->integer('energy_change')->default(0);
            $table->integer('bond_change')->default(0);

            // Rewards
            $table->json('items_used')->nullable(); // Oggetti usati nell'interazione
            $table->json('rewards_obtained')->nullable(); // Ricompense ottenute

            // Details
            $table->text('notes')->nullable();
            $table->boolean('was_successful')->default(true);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['creature_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creature_interactions');
    }
};
