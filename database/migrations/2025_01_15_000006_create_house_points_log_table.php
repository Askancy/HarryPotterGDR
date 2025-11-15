<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousePointsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_points_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('house_id');
            $table->unsignedInteger('user_id')->nullable(); // User who received/lost points
            $table->unsignedInteger('awarded_by')->nullable(); // Admin/system who awarded
            $table->integer('points'); // Can be negative
            $table->enum('type', [
                'manual',           // Admin assignment
                'quest_complete',   // Quest completed
                'achievement',      // Achievement unlocked
                'event_win',        // Event victory
                'good_behavior',    // Roleplay/forum quality
                'rule_violation',   // Penalty
                'competition',      // House competition
                'attendance',       // Daily login
                'system'            // System generated
            ])->default('manual');
            $table->string('reason')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('house_id')->references('id')->on('houses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('awarded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_points_log');
    }
}
