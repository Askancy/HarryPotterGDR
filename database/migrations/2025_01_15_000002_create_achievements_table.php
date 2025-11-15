<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable(); // FontAwesome icon
            $table->enum('category', ['combat', 'exploration', 'social', 'collection', 'mastery', 'special'])->default('special');
            $table->integer('points')->default(10); // Punti achievement
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->integer('required_value')->default(1); // Valore richiesto per sblocco
            $table->string('requirement_type')->nullable(); // es: 'kill_creatures', 'complete_quests', etc.
            $table->integer('exp_reward')->default(0);
            $table->integer('money_reward')->default(0);
            $table->boolean('is_hidden')->default(false); // Achievement segreti
            $table->timestamps();
        });

        Schema::create('user_achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('achievement_id')->unsigned();
            $table->integer('progress')->default(0); // Progresso verso il completamento
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('achievement_id')->references('id')->on('achievements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
    }
}
