<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Role extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('role', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_user');
          $table->enum('magician', ['0','1'])->nullable()->default('0'); //0 mago senza bacchetta 1 mago
          $table->enum('student', ['0', '1', '2','3','4','5'])->default('0'); //0 no studente 1-2-3-4-5 anno studente
          $table->enum('occupation', ['0', '1', '2'])->nullable()->default('0'); //0 nessuno 1- professore 2-negoziante
          $table->string('express')->nullable(); //professione trascritta
          $table->string('id_shop')->nullable()->default('0'); //id negozio se negoziante
          $table->biginteger('salary')->default('0'); // stipendio se professore o studente
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
      Schema::dropIfExists('role');
    }
}
