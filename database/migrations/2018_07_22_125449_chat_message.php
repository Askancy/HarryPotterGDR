<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chatmessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('chat_message', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_maps');
          $table->string('id_user');
          $table->string('id_dest')->nullable();
          $table->text('text');
          $table->text('type'); //0 azione 1 dado 2 sussurro pubblico 3 sussurro privato 4 oggetti 5 incantesimi 6 mostri(solo admin)
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
      Schema::dropIfExists('chat_message');
    }
}
