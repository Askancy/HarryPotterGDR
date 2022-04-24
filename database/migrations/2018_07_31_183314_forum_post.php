<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_post', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->integer('id_topic');
            $table->integer('id_section');
            $table->text('text');
            $table->enum('first',['0','1']); // 1 primo post 0 risposta
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
        Schema::dropIfExists('forum_post');
    }
}
