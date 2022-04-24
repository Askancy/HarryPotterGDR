<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Team extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('team', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->biginteger('point');
          $table->string('image')->nullable();
          $table->text('biography')->nullable();
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
      Schema::dropIfExists('team');
    }
}
