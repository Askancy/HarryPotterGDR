<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_category'); // genitore della sezione
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->integer('ordered')->default(0);
            $table->enum('status',['0','1','2'])->default(0); //0 aperta 1 solo lettura 2 solo staff
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('forum_section');
    }
}
