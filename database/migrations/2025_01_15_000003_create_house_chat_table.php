<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('house_id')->unsigned(); // 1=Grifondoro, 2=Serpeverde, 3=Corvonero, 4=Tassorosso
            $table->text('message');
            $table->string('message_type')->default('text'); // text, image, system, announcement
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('house_id')->references('id')->on('team')->onDelete('cascade');
        });

        Schema::create('house_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['meeting', 'quest', 'tournament', 'celebration', 'study'])->default('meeting');
            $table->timestamp('event_date');
            $table->integer('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('house_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('house_event_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->enum('status', ['going', 'maybe', 'declined'])->default('going');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('house_events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('house_announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id')->unsigned();
            $table->integer('posted_by')->unsigned();
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('house_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_event_participants');
        Schema::dropIfExists('house_events');
        Schema::dropIfExists('house_announcements');
        Schema::dropIfExists('house_messages');
    }
}
