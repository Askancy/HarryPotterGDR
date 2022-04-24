<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Quest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('quest', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name'); // 0 quest principale
          $table->string('id_quest')->default('0'); // 0 quest principale
          $table->string('id_maps')->default('0'); // 0 quest principale
          $table->string('id_maps_prev')->default('0'); // mappa precedente
          $table->string('id_maps_prec')->default('0'); // mappa successiva
          $table->string('privacy')->default('0'); // 0 quest pubblica 1 privata se privata guarda in pivot_quest chi ha il permesso
          $table->string('status')->default('0'); // 0 non attiva 1 attiva 2 terminata e non attiva
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
      Schema::dropIfExists('quest');
    }
}
