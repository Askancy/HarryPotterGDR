<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_report', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->integer('post_id');
          $table->enum('status', ['0','1']); // 0 aperto 1 chiuso
          $table->text('text')->nullable();
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
        Schema::dropIfExists('forum_report');
    }
}
