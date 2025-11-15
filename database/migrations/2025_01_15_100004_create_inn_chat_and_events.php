<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInnChatAndEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create inn_messages table (chat for inns/taverns)
        Schema::create('inn_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id'); // The inn/tavern
            $table->unsignedInteger('user_id');
            $table->text('message');
            $table->enum('message_type', ['text', 'action', 'system'])->default('text');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('location_shops')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['shop_id', 'created_at']);
        });

        // Create inn_visitors table (who is currently in the inn)
        Schema::create('inn_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('entered_at');
            $table->timestamp('last_activity');
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('location_shops')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['shop_id', 'user_id']);
        });

        // Create random_events table
        Schema::create('random_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['location', 'inn', 'combat', 'treasure', 'social', 'mystery']);
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->unsignedInteger('required_level')->default(1);
            $table->json('rewards')->nullable(); // e.g., {"exp": 50, "money": 100, "items": [1,2]}
            $table->json('choices')->nullable(); // Multiple choice options
            $table->unsignedInteger('duration_minutes')->default(60); // How long the event lasts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create user_random_events table (track triggered events)
        Schema::create('user_random_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('shop_id')->nullable(); // If event happened in inn
            $table->enum('status', ['active', 'completed', 'failed', 'expired'])->default('active');
            $table->json('choices_made')->nullable(); // User's choices during event
            $table->json('rewards_received')->nullable();
            $table->timestamp('triggered_at');
            $table->timestamp('expires_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('random_events')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('shop_id')->references('id')->on('location_shops')->onDelete('set null');
        });

        // Create event_participants table (for multiplayer events in inns)
        Schema::create('event_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_event_id'); // Reference to user_random_events
            $table->unsignedInteger('user_id');
            $table->enum('status', ['invited', 'joined', 'declined', 'completed'])->default('invited');
            $table->json('contribution')->nullable(); // What the user contributed
            $table->timestamps();

            $table->foreign('user_event_id')->references('id')->on('user_random_events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('user_random_events');
        Schema::dropIfExists('random_events');
        Schema::dropIfExists('inn_visitors');
        Schema::dropIfExists('inn_messages');
    }
}
