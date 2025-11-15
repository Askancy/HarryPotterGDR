<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create locations table (villages and cities)
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // e.g., "Diagon Alley", "Hogsmeade", "Godric's Hollow"
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['village', 'city', 'landmark', 'secret']);
            $table->string('image')->nullable();
            $table->unsignedInteger('required_level')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('can_have_events')->default(true);
            $table->json('coordinates')->nullable(); // For map display
            $table->timestamps();
        });

        // Create location_shops table
        Schema::create('location_shops', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->string('name'); // e.g., "Olivander", "Magie Sinister", "MondoMago"
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['wands', 'potions', 'creatures', 'books', 'clothing', 'general', 'inn', 'bank']);
            $table->string('owner_name')->nullable(); // NPC owner name
            $table->string('image')->nullable();
            $table->unsignedInteger('required_level')->default(1);
            $table->boolean('is_purchasable')->default(false); // Can users buy this shop?
            $table->unsignedBigInteger('purchase_price')->nullable();
            $table->unsignedInteger('current_owner_id')->nullable(); // User who owns it
            $table->boolean('is_active')->default(true);
            $table->json('inventory')->nullable(); // Shop inventory items
            $table->decimal('profit_percentage', 5, 2)->default(10.00); // Owner profit %
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('current_owner_id')->references('id')->on('users')->onDelete('set null');
        });

        // Create user_locations table (track visited locations)
        Schema::create('user_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('visit_count')->default(1);
            $table->timestamp('first_visited_at');
            $table->timestamp('last_visited_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unique(['user_id', 'location_id']);
        });

        // Add current_location to users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('current_location_id')->nullable()->after('team');
            $table->foreign('current_location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_location_id']);
            $table->dropColumn('current_location_id');
        });

        Schema::dropIfExists('user_locations');
        Schema::dropIfExists('location_shops');
        Schema::dropIfExists('locations');
    }
}
