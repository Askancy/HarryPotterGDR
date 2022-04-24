<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('users', function (Blueprint $table) {
          $table->increments('id');
          $table->string('username')->unique();;
          $table->enum('group', ['0', '1', '2']);
          $table->string('name');
          $table->string('surname');
          $table->string('birthday');
          $table->enum('admission', ['0','1']); // 0 se devi ancora fare il sondaggio, 1 altrimenti
          $table->enum('sex', ['0', '1']);
          $table->enum('mago', ['0', '1']);
          $table->string('exp')->nullable();
          $table->string('avatar')->nullable();
          $table->text('biography')->nullable();
          $table->enum('team', ['1', '2', '3', '4']);
          $table->boolean('level')->nullable();
          $table->string('telegram')->nullable();
          $table->biginteger('money')->nullable();
          $table->string('email')->unique();
          $table->string('password');
          $table->string('slug')->nullable();
          $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
