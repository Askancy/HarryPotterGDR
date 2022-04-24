<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_topic', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('id_user');
            $table->integer('id_section');
            $table->enum('deleted',['0','1']);
            $table->enum('locked',['0','1']);
            $table->enum('notable',['0','1']);
            $table->integer('count_view')->default(0);
            $table->string('slug')->nullable();
            $table->timestamps();
            $table->timestamp('last_msg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_topic');
    }
}
