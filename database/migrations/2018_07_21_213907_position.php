<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Position extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('position', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_user');
          $table->text('id_maps')->nullable();
          $table->text('id_shop')->nullable();
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
      Schema::dropIfExists('position');
    }
}
