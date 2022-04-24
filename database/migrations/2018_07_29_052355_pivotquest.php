<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pivotquest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('pivot_quest', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_quest')->default('0'); // 0 quest principale
          $table->string('id_user')->default('0'); // 0 quest pubblica 1 privata se privata guarda in pivot_quest chi ha il permesso
          $table->string('exp')->default('0');
          $table->string('salary')->default('0');
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
      Schema::dropIfExists('pivot_quest');
    }
}
