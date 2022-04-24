<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logspoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('logs_point', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_team');
          $table->string('point');
          $table->enum('positive', ['0', '1'])->default('0'); //0 positivo 1 negativo
          $table->string('id_user')->nullable();
          $table->text('motivation')->nullable();
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
      Schema::dropIfExists('logs_point');
    }
}
