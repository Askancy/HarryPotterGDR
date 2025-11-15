<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Nome incantesimo
            $table->string('incantation')->nullable(); // Formula magica
            $table->text('description')->nullable();
            $table->enum('type', ['attack', 'defense', 'utility', 'healing', 'curse', 'charm', 'transfiguration'])->default('utility');
            $table->integer('power')->default(0); // Potenza 1-100
            $table->integer('mana_cost')->default(10); // Costo in mana/energia
            $table->integer('required_level')->default(1); // Livello richiesto
            $table->string('element')->nullable(); // Elemento (fuoco, acqua, etc.)
            $table->integer('cooldown')->default(0); // Cooldown in secondi
            $table->integer('duration')->default(0); // Durata effetto in secondi
            $table->string('icon')->nullable(); // Icona FontAwesome
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->boolean('is_forbidden')->default(false); // Maledizioni Senza Perdono
            $table->timestamps();
        });

        Schema::create('user_spells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('spell_id')->unsigned();
            $table->integer('proficiency')->default(1); // Competenza 1-100
            $table->integer('times_used')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('spell_id')->references('id')->on('spells')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_spells');
        Schema::dropIfExists('spells');
    }
}
