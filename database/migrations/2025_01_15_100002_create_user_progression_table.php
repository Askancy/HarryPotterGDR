<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProgressionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add progression fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('current_exp')->default(0)->after('exp');
            $table->unsignedInteger('required_exp')->default(100)->after('current_exp');
            $table->unsignedInteger('skill_points')->default(0)->after('required_exp');
            $table->unsignedInteger('total_exp_earned')->default(0)->after('skill_points');
        });

        // Create skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('category', ['combat', 'magic', 'defense', 'herbology', 'potions', 'divination', 'charms', 'transfiguration']);
            $table->unsignedInteger('max_level')->default(10);
            $table->string('icon')->nullable(); // FontAwesome icon
            $table->json('bonuses')->nullable(); // e.g., {"damage": 5, "defense": 3}
            $table->timestamps();
        });

        // Create user_skills pivot table
        Schema::create('user_skills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('skill_id');
            $table->unsignedInteger('level')->default(1);
            $table->unsignedInteger('experience')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->unique(['user_id', 'skill_id']);
        });

        // Create level_rewards table
        Schema::create('level_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level');
            $table->unsignedInteger('money_reward')->default(0);
            $table->unsignedInteger('skill_points')->default(1);
            $table->string('title')->nullable(); // e.g., "Aspirante Mago", "Stregone", etc.
            $table->json('unlocks')->nullable(); // e.g., ["spells" => [1,2,3], "locations" => [4]]
            $table->timestamps();

            $table->unique('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_rewards');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('skills');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_exp', 'required_exp', 'skill_points', 'total_exp_earned']);
        });
    }
}
