<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousePointsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_points_archive', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season'); // e.g., "Anno 2025", "Semestre 1 2025"
            $table->text('houses_data'); // JSON with final standings
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_points_archive');
    }
}
