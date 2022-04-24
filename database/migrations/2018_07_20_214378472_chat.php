<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('chat', function (Blueprint $table) {
          $table->increments('id');
          $table->enum('id_team', ['0','1', '2', '3', '4']); // 0 no quest 1 quest
          $table->enum('is_quest', ['0', '1'])->default('0'); // 0 no quest 1 quest
          $table->string('id_quest')->nullable();
          $table->string('name');
          $table->text('description')->nullable();
          $table->string('image')->nullable();
          $table->string('background')->nullable();
          $table->string('restrinction')->nullable(); //0 pubblica 1 casata 2 prenotazione 3 quest 4 chiusa
          $table->string('meteo')->nullable(); //0 cronjob varia il meteo casualmente ogni 4 ore
          $table->text('html')->nullable();
          $table->text('slug')->nullable();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('chat');
    }
}
