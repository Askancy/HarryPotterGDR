<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user'); // id utente di chi scrive il messaggio
            $table->integer('id_ticket');
            $table->text('text');
            $table->integer('first'); // 1 primo post 0 risposta
            $table->integer('read'); // 1 se ho letto il messaggio, 0 altrimenti
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
        Schema::dropIfExists('ticket_message');
    }
}
